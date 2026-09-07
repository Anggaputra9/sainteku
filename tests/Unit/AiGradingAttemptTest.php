<?php

namespace Tests\Unit;

use App\Services\AiGradingService;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
class AiGradingAttemptTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public static function unfinishedAttempts(): array
    {
        return [[null], ['ONGOING'], ['NOT_STARTED']];
    }

    #[DataProvider('unfinishedAttempts')]
    public function test_missing_or_unfinished_attempt_does_not_grade(?string $status): void
    {
        $model = Mockery::mock('alias:Modules\\Ujian\\Models\\ExamAttempt');
        $attempt = $status === null ? null : Mockery::mock();
        $attempt?->shouldReceive('isFinished')->once()->andReturn(false);
        $model->shouldReceive('find')->with(7)->once()->andReturn($attempt);
        $service = Mockery::mock(AiGradingService::class)->makePartial();
        $service->shouldNotReceive('gradeAnswer');

        $result = $service->gradeExamAttempt(7);

        self::assertFalse($result['success']);
        self::assertSame(0, $result['graded_count']);
        self::assertNull($result['total_score']);
        self::assertNotEmpty($result['errors']);
    }

    public static function gradingResults(): array
    {
        return [
            'weighted total includes prior and MC grades' => [true, true, 70.0, 1],
            'failed essay remains pending' => [true, false, null, 0],
            'no new essays preserves total' => [false, true, 85.0, 0],
            'unanswered essay remains pending' => [false, true, null, 0],
        ];
    }

    #[DataProvider('gradingResults')]
    public function test_returns_recalculated_total_in_every_finished_path(bool $hasAnswer, bool $aiSuccess, ?float $total, int $count): void
    {
        require_once __DIR__.'/../../Modules/Ujian/app/Models/ExamAttemptAnswer.php';
        $model = Mockery::mock('alias:Modules\\Ujian\\Models\\ExamAttempt');
        $attempt = Mockery::mock();
        $model->shouldReceive('find')->with(7)->once()->andReturn($attempt);
        $attempt->shouldReceive('isFinished')->once()->andReturn(true);
        $attempt->shouldReceive('gradeMultipleChoice')->once();
        $attempt->shouldReceive('loadMissing')->with('room.proposal.examQuestions')->once();
        $attempt->room = (object) ['proposal' => (object) ['examQuestions' => new Collection([
            (object) ['question_id' => 3, 'weight' => 40],
        ])]];
        $query = Mockery::mock();
        $attempt->shouldReceive('answers')->once()->andReturn($query);
        $query->shouldReceive('whereIn')->with('question_id', Mockery::on(fn ($ids) => $ids->all() === [3]))->once()->andReturnSelf();
        $query->shouldReceive('whereHas')->with('question', Mockery::on(function ($filter) {
            $questionQuery = Mockery::mock();
            $questionQuery->shouldReceive('where')->with('question_type', 'essay')->once();
            $filter($questionQuery);
            return true;
        }))->once()->andReturnSelf();
        $query->shouldReceive('where')->with('is_answered', true)->once()->andReturnSelf();
        $query->shouldReceive('whereNull')->with('score')->once()->andReturnSelf();
        $answer = Mockery::mock(\Modules\Ujian\Models\ExamAttemptAnswer::class)->makePartial();
        $answer->id = 9;
        $answer->question_id = 3;
        $query->shouldReceive('get')->once()->andReturn(new Collection($hasAnswer ? [$answer] : []));
        $service = Mockery::mock(AiGradingService::class)->makePartial();
        if ($hasAnswer) {
            $service->shouldReceive('gradeAnswer')->with($answer, null)->once()->andReturn([
                'success' => $aiSuccess, 'score' => 75, 'feedback' => 'Feedback', 'error' => 'AI unavailable',
            ]);
        } else {
            $service->shouldNotReceive('gradeAnswer');
        }
        if ($hasAnswer && $aiSuccess) {
            $model->shouldReceive('weightedScoreFromPercentage')->with(75, 40.0)->once()->andReturn(30.0);
            $answer->shouldReceive('update')->with(Mockery::on(fn ($data) =>
                $data['score'] === 30.0 && $data['grading_method'] === 'ai'
                && $data['ai_feedback'] === 'Feedback' && $data['grader_note'] === null
                && $data['graded_at'] instanceof \DateTimeInterface
            ))->once()->andReturn(true);
        } else {
            $answer->shouldNotReceive('update');
        }
        $attempt->shouldReceive('recalculateScore')->once()->andReturn($total);

        $result = $service->gradeExamAttempt(7);

        self::assertSame($aiSuccess, $result['success']);
        self::assertSame($count, $result['graded_count']);
        self::assertSame($total, $result['total_score']);
        self::assertArrayNotHasKey('average_score', $result);
        self::assertCount($aiSuccess ? 0 : 1, $result['errors']);
    }
}
