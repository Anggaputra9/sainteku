<?php

namespace Modules\MonevAkademik\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Question extends Model
{
    use HasUuids;

    protected $table = 'trx_questions';

    protected $fillable = [
        'course_id',
        'cpmk_id',
        'question_text',
        'question_type',
        'options',
        'correct_option',
        'image_path',
        'created_by',
    ];

    protected $casts = [
        'options' => 'array',
        'cpmk_id' => 'array', // Otomatis jadi array pas dipanggil di PHP
    ];

    protected $attributes = ['question_type' => 'essay'];

    protected $hidden = ['correct_option'];

    public function isMultipleChoice(): bool
    {
        return $this->question_type === 'multiple_choice';
    }

    public static function validateQuestions(\Illuminate\Http\Request $request): void
    {
        $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.weight' => 'required|numeric|gt:0|max:100',
            'questions.*.question_type' => 'sometimes|required|in:essay,multiple_choice',
            'questions.*.options' => 'nullable|array:A,B,C,D,E',
            'questions.*.options.*' => 'nullable|string|max:2000',
            'questions.*.correct_option' => 'nullable|string|in:A,B,C,D,E',
        ]);
        foreach ($request->input('questions') as $index => $question) {
            if (($question['question_type'] ?? 'essay') !== 'multiple_choice') {
                continue;
            }
            $options = array_filter($question['options'] ?? [], fn ($value) => trim((string) $value) !== '');
            if (count($options) < 2 || !isset($options[$question['correct_option'] ?? ''])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "questions.$index.options" => 'Pilihan ganda memerlukan minimal dua opsi dan satu kunci jawaban yang valid.',
                ]);
            }
        }
    }

    public static function typeAttributes(array $question): array
    {
        $type = $question['question_type'] ?? 'essay';
        return [
            'question_type' => $type,
            'options' => $type === 'multiple_choice'
                ? array_filter($question['options'] ?? [], fn ($value) => trim((string) $value) !== '') : null,
            'correct_option' => $type === 'multiple_choice' ? $question['correct_option'] : null,
        ];
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function course()
    {
        return $this->belongsTo(\App\Models\MstCourse::class, 'course_id', 'id');
    }

    // ❌ KITA HAPUS/COMMENT RELASI INI KARENA UDAH JADI ARRAY
    // public function cpmk()
    // {
    //     return $this->belongsTo(\App\Models\MstCpmk::class, 'cpmk_id', 'id');
    // }

    // ✅ KITA GANTI PAKE ACCESSOR INI BUAT NARIK DATA MULTI-CPMK
    public function getCpmkDetailsAttribute()
    {
        // Kalau cpmk_id kosong atau bukan array, balikin array kosong
        if (empty($this->cpmk_id) || !is_array($this->cpmk_id)) {
            return [];
        }

        return \App\Models\MstCpmk::query()
            ->where('course_id', $this->course_id)
            ->whereIn('id', $this->cpmk_id)
            ->select('id', 'name')
            ->get();
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class, 'question_id', 'id');
    }
}
