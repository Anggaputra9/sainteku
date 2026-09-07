<?php

namespace Tests\Unit;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;

class MixedExamMigrationTest extends TestCase
{
    public function test_upgrade_preserves_legacy_data_and_accepts_new_values(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required.');
        }
        $container = new Container;
        $db = new Capsule($container);
        $db->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $db->setAsGlobal();
        $container->instance('db', $db->getDatabaseManager());
        $container->bind('db.schema', fn () => $db->schema());
        Facade::setFacadeApplication($container);

        try {
            Capsule::schema()->create('trx_exam_proposals', function (Blueprint $table) {
                $table->id();
                $table->enum('exam_type', ['UTS', 'UAS']);
            });
            Capsule::schema()->create('trx_questions', function (Blueprint $table) {
                $table->id();
                $table->text('question_text');
            });
            Capsule::schema()->create('trx_exam_attempt_answers', function (Blueprint $table) {
                $table->id();
                $table->text('answer_text')->nullable();
                $table->decimal('score')->nullable();
                $table->enum('grading_method', ['manual', 'ai'])->nullable();
            });
            Capsule::table('trx_exam_proposals')->insert(['exam_type' => 'UTS']);
            Capsule::table('trx_questions')->insert(['question_text' => 'Legacy essay']);
            Capsule::table('trx_exam_attempt_answers')->insert([
                'answer_text' => 'Legacy answer', 'score' => 75, 'grading_method' => 'manual',
            ]);

            $questions = require __DIR__.'/../../Modules/MonevAkademik/database/migrations/2026_09_07_100000_add_mixed_question_types.php';
            $answers = require __DIR__.'/../../Modules/Ujian/database/migrations/2026_09_07_100001_add_selected_option_to_exam_answers.php';
            $questions->up();
            $answers->up();

            self::assertSame('essay', Capsule::table('trx_questions')->value('question_type'));
            self::assertSame('Legacy answer', Capsule::table('trx_exam_attempt_answers')->value('answer_text'));
            self::assertEquals(75, Capsule::table('trx_exam_attempt_answers')->value('score'));
            self::assertSame('manual', Capsule::table('trx_exam_attempt_answers')->value('grading_method'));
            Capsule::table('trx_exam_proposals')->insert(['exam_type' => 'QUIZ']);
            Capsule::table('trx_exam_attempt_answers')->insert(['selected_option' => 'B', 'grading_method' => 'automatic']);
            self::assertSame(2, Capsule::table('trx_exam_proposals')->count());

            $answers->down();
            $questions->down();
            self::assertSame('QUIZ', Capsule::table('trx_exam_proposals')->where('id', 2)->value('exam_type'));
            self::assertSame('Legacy answer', Capsule::table('trx_exam_attempt_answers')->where('id', 1)->value('answer_text'));
        } finally {
            Facade::clearResolvedInstances();
            Facade::setFacadeApplication(null);
        }
    }
}
