<?php

namespace Tests\Unit;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Modules\MonevAkademik\app\Models\Question;
use PHPUnit\Framework\TestCase;

class QuestionValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $container = new Container;
        $factory = new Factory(new Translator(new ArrayLoader, 'en'), $container);
        $container->instance('validator', $factory);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($container);
        Request::macro('validate', function (array $rules) use ($factory) {
            return $factory->make($this->all(), $rules)->validate();
        });
    }

    protected function tearDown(): void
    {
        Request::flushMacros();
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
        parent::tearDown();
    }

    public function test_legacy_essay_and_mc_can_share_a_package(): void
    {
        $request = new Request(['questions' => [
            ['question_text' => 'Essay', 'weight' => 60],
            ['question_text' => 'Choose', 'weight' => 40, 'question_type' => 'multiple_choice',
                'options' => ['A' => 'Zero', 'B' => 'One', 'C' => ''], 'correct_option' => 'A'],
        ]]);
        Question::validateQuestions($request);
        self::assertCount(2, Question::typeAttributes($request->input('questions.1'))['options']);
    }

    public function test_key_must_reference_a_nonempty_option(): void
    {
        $this->expectException(ValidationException::class);
        Question::validateQuestions(new Request(['questions' => [
            ['question_text' => 'Choose', 'weight' => 100, 'question_type' => 'multiple_choice',
                'options' => ['A' => 'One', 'B' => 'Two', 'C' => '  '], 'correct_option' => 'C'],
        ]]));
    }

    public function test_mc_requires_at_least_two_options(): void
    {
        $this->expectException(ValidationException::class);
        Question::validateQuestions(new Request(['questions' => [
            ['question_text' => 'Choose', 'weight' => 100, 'question_type' => 'multiple_choice',
                'options' => ['A' => 'Only'], 'correct_option' => 'A'],
        ]]));
    }

    public function test_array_answer_key_is_rejected_as_validation_error(): void
    {
        $this->expectException(ValidationException::class);
        Question::validateQuestions(new Request(['questions' => [
            ['question_text' => 'Choose', 'weight' => 100, 'question_type' => 'multiple_choice',
                'options' => ['A' => 'One', 'B' => 'Two'], 'correct_option' => ['A']],
        ]]));
    }
}
