<?php

namespace Tests\Unit;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Modules\MonevAkademik\app\Models\Question;
use Modules\Ujian\Models\ExamAttempt;
use Modules\Ujian\Models\ExamAttemptAnswer;
use PHPUnit\Framework\TestCase;

// Standalone PHPUnit does not boot the module service provider's autoloader.
require_once __DIR__.'/../../Modules/Ujian/app/Models/ExamRoom.php';
require_once __DIR__.'/../../Modules/Ujian/app/Models/ExamAttemptAnswer.php';
require_once __DIR__.'/../../Modules/Ujian/app/Models/ExamAttempt.php';

class MixedExamTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required.');
        }
        $container = new Container;
        $db = new Capsule($container);
        $container->instance('date', new \Illuminate\Support\DateFactory);
        $container->instance('db', $db->getDatabaseManager());
        $container->bind('db.schema', fn () => $db->schema());
        \Illuminate\Support\Facades\Facade::setFacadeApplication($container);
        $db->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $db->setEventDispatcher(new Dispatcher(new Container));
        $db->setAsGlobal();
        $db->bootEloquent();
        \Illuminate\Database\Eloquent\Model::clearBootedModels();
        Capsule::schema()->create('trx_questions', function (Blueprint $table) {
            $table->id();
        });
        Capsule::schema()->create('trx_exam_proposals', function (Blueprint $table) {
            $table->id();
            $table->enum('exam_type', ['UTS', 'UAS']);
        });
        Capsule::schema()->create('trx_exam_rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('proposal_id');
        });
        Capsule::schema()->create('trx_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('proposal_id');
            $table->integer('question_id');
            $table->integer('order_no');
            $table->decimal('weight');
        });
        Capsule::schema()->create('trx_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->integer('room_id');
            $table->string('status');
            $table->decimal('score')->nullable();
            $table->timestamps();
        });
        Capsule::schema()->create('trx_exam_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('attempt_id');
            $table->integer('question_id');
            $table->text('answer_text')->nullable();
            $table->boolean('is_answered')->default(false);
            $table->decimal('score')->nullable();
            $table->enum('grading_method', ['manual', 'ai'])->nullable();
            $table->text('grader_note')->nullable();
            $table->text('ai_feedback')->nullable();
            $table->string('graded_by')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
            $table->unique(['attempt_id', 'question_id']);
        });
        $questions = require __DIR__.'/../../Modules/MonevAkademik/database/migrations/2026_09_07_100000_add_mixed_question_types.php';
        $answers = require __DIR__.'/../../Modules/Ujian/database/migrations/2026_09_07_100001_add_selected_option_to_exam_answers.php';
        $questions->up();
        $answers->up();
        Capsule::table('trx_exam_proposals')->insert(['id' => 1, 'exam_type' => 'QUIZ']);
        Capsule::table('trx_exam_rooms')->insert(['id' => 1, 'proposal_id' => 1]);
        Capsule::table('trx_exam_attempts')->insert(['id' => 1, 'room_id' => 1, 'status' => 'ONGOING']);
    }

    protected function tearDown(): void
    {
        \Illuminate\Support\Facades\Facade::clearResolvedInstances();
        \Illuminate\Support\Facades\Facade::setFacadeApplication(null);
        parent::tearDown();
    }

    private function question(int $id, float $weight, bool $mc = false): void
    {
        Capsule::table('trx_questions')->insert([
            'id' => $id,
            'question_type' => $mc ? 'multiple_choice' : 'essay',
            'options' => $mc ? json_encode(['A' => 'First', 'B' => 'Second']) : null,
            'correct_option' => $mc ? 'B' : null,
        ]);
        Capsule::table('trx_exam_questions')->insert([
            'proposal_id' => 1, 'question_id' => $id, 'order_no' => $id, 'weight' => $weight,
        ]);
    }

    public function test_legacy_question_defaults_to_essay(): void
    {
        Capsule::table('trx_questions')->insert(['id' => 1]);
        $question = Question::findOrFail(1);
        self::assertFalse($question->isMultipleChoice());
        self::assertSame('essay', $question->question_type);
        self::assertSame(['question_type' => 'essay', 'options' => null, 'correct_option' => null], Question::typeAttributes([]));
    }

    public function test_answer_key_is_private_unless_explicitly_exposed(): void
    {
        $question = new Question(['question_type' => 'multiple_choice', 'correct_option' => 'B']);
        self::assertSame('B', $question->correct_option);
        self::assertArrayNotHasKey('correct_option', $question->toArray());
        self::assertSame('B', $question->makeVisible('correct_option')->toArray()['correct_option']);
    }

    public function test_ongoing_attempt_cannot_be_automatically_graded(): void
    {
        $this->question(1, 100, true);
        $attempt = ExamAttempt::findOrFail(1);
        $attempt->gradeMultipleChoice();
        self::assertSame(0, $attempt->answers()->count());
        self::assertNull($attempt->fresh()->score);
    }

    public function test_partial_essay_grading_remains_pending(): void
    {
        $this->question(1, 40);
        $this->question(2, 60);
        ExamAttemptAnswer::create(['attempt_id' => 1, 'question_id' => 1, 'score' => 30]);
        $attempt = ExamAttempt::findOrFail(1);
        self::assertNull($attempt->recalculateScore());
        self::assertNull($attempt->fresh()->score);
    }

    public function test_mixed_submission_grades_mc_but_preserves_essay_and_waits_for_manual_grade(): void
    {
        $this->question(1, 40, true);
        $this->question(2, 60);
        ExamAttemptAnswer::create(['attempt_id' => 1, 'question_id' => 1, 'selected_option' => 'B', 'is_answered' => true]);
        $essay = ExamAttemptAnswer::create(['attempt_id' => 1, 'question_id' => 2, 'answer_text' => 'Legacy essay', 'is_answered' => true]);
        $attempt = ExamAttempt::findOrFail(1);
        $attempt->update(['status' => 'SUBMITTED']);
        $mc = ExamAttemptAnswer::where('question_id', 1)->firstOrFail();
        self::assertSame('40.00', $mc->score);
        self::assertSame('automatic', $mc->grading_method);
        self::assertNull($attempt->fresh()->score);
        self::assertSame('Legacy essay', $essay->fresh()->answer_text);
        self::assertNull($attempt->recalculateScore());
        self::assertNull($essay->fresh()->score);
        $essay->update(['score' => 45, 'grading_method' => 'manual']);
        // The attempt already has loaded answers; recalculation must fetch fresh grades.
        self::assertSame(85.0, $attempt->recalculateScore());
        $attempt->gradeMultipleChoice();
        self::assertSame('45.00', $essay->fresh()->score);
        self::assertSame('85.00', $attempt->fresh()->score);
    }

    public function test_incorrect_and_unanswered_mc_receive_zero_on_auto_submit(): void
    {
        $this->question(1, 50, true);
        $this->question(2, 50, true);
        ExamAttemptAnswer::create(['attempt_id' => 1, 'question_id' => 1, 'selected_option' => 'A', 'is_answered' => true]);
        $attempt = ExamAttempt::findOrFail(1);
        $attempt->update(['status' => 'AUTO_SUBMITTED_TIME']);
        self::assertSame('0.00', $attempt->fresh()->score);
        self::assertSame(2, $attempt->answers()->where('grading_method', 'automatic')->count());
        self::assertSame('0.00', $attempt->answers()->where('question_id', 2)->firstOrFail()->score);
        $attempt->gradeMultipleChoice();
        self::assertSame(2, $attempt->answers()->count());
    }

    public function test_violation_submission_grades_once_and_later_updates_do_not_regrade(): void
    {
        $this->question(1, 100, true);
        ExamAttemptAnswer::create(['attempt_id' => 1, 'question_id' => 1, 'selected_option' => 'B']);
        $attempt = ExamAttempt::findOrFail(1);
        $attempt->update(['status' => 'AUTO_SUBMITTED_VIOLATION']);
        self::assertSame('100.00', $attempt->fresh()->score);
        self::assertSame('QUIZ', $attempt->room->proposal->exam_type);

        Capsule::table('trx_questions')->where('id', 1)->update(['correct_option' => 'A']);
        $attempt->update(['score' => 90]);
        self::assertSame('90.00', $attempt->fresh()->score);
        self::assertSame('100.00', $attempt->answers()->firstOrFail()->score);
        self::assertSame(1, $attempt->answers()->count());
    }
}
