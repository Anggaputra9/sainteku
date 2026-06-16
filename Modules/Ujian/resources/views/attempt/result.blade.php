@extends('layouts.app')

@section('content')
    @php
        $room = $attempt->room;
        $proposal = $room->proposal;
        $course = $proposal?->course;
        $answered = $attempt->answers->where('is_answered', true)->count();
        $gradedCount = $attempt->answers->whereNotNull('score')->count();
        $allGraded = $totalQuestions > 0 && $gradedCount === $totalQuestions;

        $durationLabel = '—';
        if ($attempt->started_at && $attempt->submitted_at) {
            $mins = $attempt->started_at->diffInMinutes($attempt->submitted_at);
            $durationLabel = $mins < 1
                ? $attempt->started_at->diffInSeconds($attempt->submitted_at) . ' detik'
                : $mins . ' menit';
        }

        $statusConfig = match ($attempt->status) {
            'SUBMITTED' => [
                'badge' => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800',
                'hero'  => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400',
                'icon'  => 'fa-circle-check',
            ],
            'AUTO_SUBMITTED_TIME' => [
                'badge' => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800',
                'hero'  => 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                'icon'  => 'fa-hourglass-end',
            ],
            'AUTO_SUBMITTED_VIOLATION' => [
                'badge' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800',
                'hero'  => 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                'icon'  => 'fa-ban',
            ],
            'ONGOING' => [
                'badge' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
                'hero'  => 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                'icon'  => 'fa-spinner',
            ],
            default => [
                'badge' => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-700/50 dark:text-gray-300 dark:border-gray-600',
                'hero'  => 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400',
                'icon'  => 'fa-circle-info',
            ],
        };

        $btnGray = 'inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 shadow-sm hover:bg-gray-300 focus:outline-none dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 sm:px-6 sm:py-2.5 sm:text-sm transition-all';
        $btnBlue = 'inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 focus:outline-none sm:px-6 sm:py-2.5 sm:text-sm transition-all';
        $btnPurple = 'inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-purple-600 px-3 py-2 text-xs font-bold text-white shadow-md shadow-purple-600/20 hover:bg-purple-700 focus:outline-none sm:px-6 sm:py-2.5 sm:text-sm transition-all';
        $btnGreen = 'inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-green-600 px-3 py-2 text-xs font-bold text-white shadow-md shadow-green-600/20 hover:bg-green-700 focus:outline-none sm:px-6 sm:py-2.5 sm:text-sm transition-all';
        $btnEmerald = 'inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-700 focus:outline-none sm:px-6 sm:py-2.5 sm:text-sm transition-all';
        $inputClass = 'w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:outline-none placeholder:text-gray-400 dark:border-gray-600 dark:bg-[#0f172a] dark:text-white dark:placeholder:text-gray-500';
        $cardClass = 'rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]';
        $cardHeaderClass = 'px-4 py-3 border-b border-gray-100 dark:border-gray-700';
        $fieldLabel = 'text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500';
        $fieldValue = 'mt-1 text-sm font-semibold text-gray-900 dark:text-white';
        $statCardClass = 'rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-[#1e293b]';
    @endphp

    <div class="max-w-5xl mx-auto space-y-6 print-area">

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 pb-4 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700 no-print">
            <div>
                <h2 class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-clipboard-check text-indigo-500 dark:text-indigo-400"></i>
                    {{ $isLecturer ? 'Hasil Ujian' : 'Ringkasan Jawaban' }}
                </h2>
                <nav>
                    <ol class="flex flex-wrap items-center gap-2 mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                        <li>Ujian /</li>
                        <li class="text-indigo-600 dark:text-indigo-400">{{ $room->title }}</li>
                    </ol>
                </nav>
            </div>
            <span class="inline-flex self-start rounded-md px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider border {{ $statusConfig['badge'] }}">
                {{ $attempt->statusLabel() }}
            </span>
        </div>

        {{-- HERO --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden dark:border-gray-700 dark:bg-[#1e293b]">
            <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center gap-5">
                <div class="flex items-center gap-4 min-w-0 flex-1">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl {{ $statusConfig['hero'] }}">
                        <i class="fas {{ $statusConfig['icon'] }} text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Peserta</div>
                        <div class="mt-0.5 text-lg font-bold text-gray-900 dark:text-white truncate">{{ $attempt->user?->name ?? '—' }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $attempt->user?->identity_id ?? '—' }}</div>
                    </div>
                </div>
                @if ($isLecturer)
                    <div class="shrink-0 text-center sm:text-right sm:pl-6 sm:border-l border-gray-100 dark:border-gray-700">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Total Skor</div>
                        @if ($attempt->score !== null)
                            <div class="mt-1 text-4xl font-black tabular-nums text-indigo-600 dark:text-indigo-400">{{ $attempt->score }}</div>
                        @else
                            <div class="mt-1 text-sm font-semibold italic text-gray-400 dark:text-gray-500">Belum dinilai</div>
                        @endif
                        @if ($allGraded)
                            <div class="mt-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">Semua soal dinilai</div>
                        @elseif ($gradedCount > 0)
                            <div class="mt-1 text-[11px] font-semibold text-amber-600 dark:text-amber-400">{{ $gradedCount }}/{{ $totalQuestions }} dinilai</div>
                        @endif
                    </div>
                @else
                    <div class="shrink-0 text-center sm:text-right sm:pl-6 sm:border-l border-gray-100 dark:border-gray-700">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Status</div>
                        <div class="mt-1 text-sm font-bold text-emerald-600 dark:text-emerald-400">Jawaban tersimpan</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Informasi Ujian --}}
        <div class="{{ $cardClass }} overflow-hidden">
            <div class="{{ $cardHeaderClass }}">
                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                    <i class="fa-solid fa-book text-indigo-500 dark:text-indigo-400"></i> Informasi Ujian
                </h4>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <div>
                    <div class="{{ $fieldLabel }}">Ruang Ujian</div>
                    <div class="{{ $fieldValue }}">{{ $room->title }}</div>
                </div>
                <div>
                    <div class="{{ $fieldLabel }}">Mata Kuliah</div>
                    <div class="{{ $fieldValue }}">{{ $course?->course_name ?? '—' }}</div>
                </div>
                <div>
                    <div class="{{ $fieldLabel }}">Program Studi</div>
                    <div class="{{ $fieldValue }}">{{ $course?->unit?->unit_name ?? '—' }}</div>
                </div>
                <div>
                    <div class="{{ $fieldLabel }}">Jenis Ujian</div>
                    <div class="{{ $fieldValue }}">{{ $proposal?->exam_type ?? '—' }}</div>
                </div>
                <div>
                    <div class="{{ $fieldLabel }}">Periode</div>
                    <div class="{{ $fieldValue }}">{{ $proposal?->period?->display_label ?? '—' }}</div>
                </div>
                <div>
                    <div class="{{ $fieldLabel }}">Kode Ruang</div>
                    <div class="mt-1">
                        <span class="inline-flex items-center gap-1.5 rounded-md bg-indigo-50 px-2.5 py-1 font-mono text-sm font-bold text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800">
                            <i class="fas fa-key text-[10px]"></i> {{ $room->room_code }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="{{ $statCardClass }}">
                <div class="{{ $fieldLabel }}">Soal Dijawab</div>
                <div class="mt-1 font-bold text-gray-900 dark:text-white tabular-nums">{{ $answered }}/{{ $totalQuestions }}</div>
            </div>
            <div class="{{ $statCardClass }}">
                <div class="{{ $fieldLabel }}">Pelanggaran</div>
                <div class="mt-1 font-bold text-gray-900 dark:text-white tabular-nums">{{ $attempt->tab_switch_count }}x</div>
            </div>
            <div class="{{ $statCardClass }}">
                <div class="{{ $fieldLabel }}">Durasi Pengerjaan</div>
                <div class="mt-1 text-sm font-bold text-gray-900 dark:text-white">{{ $durationLabel }}</div>
            </div>
            <div class="{{ $statCardClass }}">
                <div class="{{ $fieldLabel }}">Durasi Ujian</div>
                <div class="mt-1 text-sm font-bold text-gray-900 dark:text-white">{{ $room->duration_minutes }} menit</div>
            </div>
        </div>

        {{-- Waktu --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="{{ $statCardClass }}">
                <div class="{{ $fieldLabel }}">Mulai Mengerjakan</div>
                <div class="mt-1 text-sm font-bold text-gray-900 dark:text-white">{{ $attempt->started_at?->translatedFormat('d M Y H:i') ?? '—' }}</div>
            </div>
            <div class="{{ $statCardClass }}">
                <div class="{{ $fieldLabel }}">Submit</div>
                <div class="mt-1 text-sm font-bold text-gray-900 dark:text-white">{{ $attempt->submitted_at?->translatedFormat('d M Y H:i') ?? '—' }}</div>
            </div>
            <div class="{{ $statCardClass }}">
                <div class="{{ $fieldLabel }}">Aktivitas Terakhir</div>
                <div class="mt-1 text-sm font-bold text-gray-900 dark:text-white">{{ $attempt->last_activity_at?->translatedFormat('d M Y H:i') ?? '—' }}</div>
            </div>
        </div>

        {{-- Jawaban --}}
        @if ($isLecturer || $attempt->isFinished())
            <div class="{{ $cardClass }} overflow-hidden"
                @if ($isLecturer) x-data="gradingApp()" x-init="init()" :class="gradingMode ? 'border-blue-400 dark:border-blue-600' : ''" @endif>
                <div class="px-4 py-3 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 no-print transition-colors
                    @if ($isLecturer) bg-gray-50 border-gray-200 dark:bg-gray-800/40 dark:border-gray-700 @else bg-gray-50 border-gray-200 dark:bg-gray-800/40 dark:border-gray-700 @endif"
                    @if ($isLecturer)
                        :class="gradingMode
                            ? 'bg-blue-600 border-blue-700 dark:bg-blue-700 dark:border-blue-800'
                            : 'bg-gray-50 border-gray-200 dark:bg-gray-800/40 dark:border-gray-700'"
                    @endif>
                    <h4 class="text-sm font-bold flex items-center gap-2 text-gray-700 dark:text-gray-200"
                        @if ($isLecturer) :class="gradingMode ? 'text-white' : 'text-gray-700 dark:text-gray-200'" @endif>
                        <i class="fa-solid fa-list-check text-indigo-500 dark:text-indigo-400"
                            @if ($isLecturer) :class="gradingMode ? 'text-blue-100' : 'text-indigo-500 dark:text-indigo-400'" @endif></i>
                        Jawaban
                        <span class="text-xs font-semibold text-gray-400"
                            @if ($isLecturer) :class="gradingMode ? 'text-blue-100/80' : 'text-gray-400'" @endif>({{ $totalQuestions }} soal)</span>
                        @if ($isLecturer)
                            <span x-show="gradingMode" x-cloak
                                class="inline-flex rounded-md bg-white/20 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white border border-white/25">
                                Mode Koreksi
                            </span>
                        @endif
                    </h4>
                    @if ($isLecturer)
                        <div class="flex flex-wrap items-center gap-2">
                            @if ($attempt->isFinished())
                                <button type="button" @click="gradeAllWithAi()" :disabled="submitting" class="{{ $btnPurple }} disabled:opacity-60 disabled:cursor-not-allowed">
                                    <i class="fas fa-robot"></i> Koreksi AI
                                </button>
                                <button type="button" @click="toggleGradingMode()"
                                    :class="gradingMode ? '{{ $btnGray }}' : '{{ $btnBlue }}'">
                                    <i class="fas" :class="gradingMode ? 'fa-eye' : 'fa-edit'"></i>
                                    <span x-text="gradingMode ? 'Mode Lihat' : 'Mode Koreksi'"></span>
                                </button>
                            @endif
                            @if ($attempt->isFinished() && $allGraded)
                                <button type="button" @click="printResult()" class="{{ $btnGreen }}">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($room->proposal->examQuestions->sortBy('order_no') as $eq)
                        @php
                            $ans = $attempt->answers->firstWhere('question_id', $eq->question_id);
                            $hasAnswer = $ans && trim($ans->answer_text ?? '') !== '';
                        @endphp
                        <div class="p-4 sm:p-5">
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-sm font-black text-indigo-700 border border-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800">
                                    {{ $eq->order_no }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                        @if ($isLecturer)
                                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                                Bobot {{ $eq->weight }}%
                                            </div>
                                        @else
                                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                                Soal {{ $eq->order_no }}
                                            </div>
                                            @if ($hasAnswer)
                                                <span class="inline-flex rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800">
                                                    Terjawab
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-500 border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700">
                                                    Kosong
                                                </span>
                                            @endif
                                        @endif
                                        @if ($isLecturer)
                                            @if ($ans && $ans->score !== null)
                                                <span class="inline-flex rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800">
                                                    Skor {{ $ans->score }}
                                                </span>
                                            @elseif ($hasAnswer)
                                                <span class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-500 border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700">
                                                    Belum dinilai
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-900 dark:text-white leading-relaxed">{!! nl2br(e($eq->question?->question_text ?? '—')) !!}</div>

                                    <div class="mt-3 rounded-xl border border-gray-200 bg-slate-50 p-3.5 dark:border-gray-700 dark:bg-[#0f172a]">
                                        <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1.5">Jawaban Mahasiswa</div>
                                        @if ($hasAnswer)
                                            <div class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap leading-relaxed">{{ $ans->answer_text }}</div>
                                        @else
                                            <span class="text-xs italic text-gray-400 dark:text-gray-500">Tidak dijawab</span>
                                        @endif
                                    </div>

                                    @if ($isLecturer && $attempt->isFinished())
                                        <div x-show="gradingMode" x-transition x-cloak class="mt-3 {{ $cardClass }} overflow-hidden border-blue-200 dark:border-blue-800 no-print">
                                            <div class="{{ $cardHeaderClass }} bg-blue-600/5 dark:bg-blue-900/20 border-blue-100 dark:border-blue-900/40">
                                                <h5 class="text-xs font-bold text-blue-800 dark:text-blue-300 flex items-center gap-2">
                                                    <i class="fas fa-pen"></i> Penilaian Soal {{ $eq->order_no }}
                                                </h5>
                                            </div>
                                            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block {{ $fieldLabel }} mb-2">Skor (0–100)</label>
                                                    <input type="number" min="0" max="100" step="0.01"
                                                        x-model="scores[{{ $eq->question_id }}].score"
                                                        @input="if($event.target.value > 100) $event.target.value = 100; if($event.target.value < 0) $event.target.value = 0;"
                                                        class="{{ $inputClass }}" placeholder="0">
                                                </div>
                                                <div class="flex items-end">
                                                    <button type="button" @click="gradeWithAi({{ $eq->question_id }}, {{ $ans ? $ans->id : 'null' }})" :disabled="submitting"
                                                        class="{{ $btnPurple }} w-full justify-center disabled:opacity-60 disabled:cursor-not-allowed">
                                                        <i class="fas fa-robot"></i> Koreksi AI
                                                    </button>
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label class="block {{ $fieldLabel }} mb-2">Catatan Koreksi (Opsional)</label>
                                                    <textarea rows="2"
                                                        x-model="scores[{{ $eq->question_id }}].grader_note"
                                                        class="{{ $inputClass }}"
                                                        placeholder="Catatan untuk mahasiswa..."></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($ans && ($ans->grader_note || $ans->ai_feedback))
                                            <div x-show="!gradingMode" class="mt-3 rounded-xl border border-blue-200 bg-blue-50 p-3.5 dark:bg-blue-900/20 dark:border-blue-900/40">
                                                <div class="text-[10px] font-bold uppercase tracking-widest text-blue-700 dark:text-blue-300 mb-1.5">
                                                    @if ($ans->grading_method === 'ai')
                                                        <i class="fas fa-robot"></i> Feedback AI
                                                    @else
                                                        <i class="fas fa-comment-dots"></i> Catatan Dosen
                                                    @endif
                                                </div>
                                                <div class="text-sm text-blue-800 dark:text-blue-200 leading-relaxed">
                                                    {{ $ans->grading_method === 'ai' ? $ans->ai_feedback : $ans->grader_note }}
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($isLecturer && $attempt->isFinished())
                    <div x-show="gradingMode" x-transition x-cloak
                        class="shrink-0 border-t border-gray-200 bg-gray-50 px-4 sm:px-6 py-4 dark:bg-gray-800/40 dark:border-gray-700 flex flex-row flex-nowrap items-center justify-between gap-2 no-print">
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-300 hidden sm:block">
                            <i class="fas fa-circle-info text-blue-500 dark:text-blue-400"></i> Isi skor tiap soal lalu simpan penilaian
                        </p>
                        <div class="flex shrink-0 items-center gap-2 sm:gap-3 ml-auto">
                            <button type="button" @click="toggleGradingMode()" class="{{ $btnGray }}">
                                <i class="fas fa-times"></i> Batal
                            </button>
                            <button type="button" @click="submitGrading()" :disabled="submitting" class="{{ $btnBlue }} disabled:opacity-60 disabled:cursor-not-allowed">
                                <i class="fas fa-save"></i>
                                <span x-text="submitting ? 'Menyimpan…' : 'Simpan Penilaian'"></span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="flex justify-end no-print">
            @if ($isLecturer)
                <a href="{{ route('ujian.rooms.index') }}" class="{{ $btnGray }}">
                    <i class="fas fa-arrow-left"></i> Kembali ke Ruang Ujian
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="{{ $btnGray }}">
                    <i class="fas fa-house"></i> Kembali ke Dashboard
                </a>
            @endif
        </div>
    </div>
@endsection

@if ($isLecturer)
@push('scripts')
<script>
function gradingApp() {
    return {
        gradingMode: false,
        submitting: false,
        scores: {},

        init() {
            @foreach($room->proposal->examQuestions as $eq)
                @php
                    $ans = $attempt->answers->firstWhere('question_id', $eq->question_id);
                @endphp
                this.scores[{{ $eq->question_id }}] = {
                    answer_id: {{ $ans ? $ans->id : 'null' }},
                    score: {{ $ans && $ans->score !== null ? $ans->score : 'null' }},
                    grader_note: @json($ans?->grader_note ?? '')
                };
            @endforeach
        },

        toggleGradingMode() {
            this.gradingMode = !this.gradingMode;
        },

        async gradeWithAi(questionId, answerId) {
            if (this.submitting) return;
            if (!answerId) {
                alert('Jawaban tidak ditemukan.');
                return;
            }
            const ok = await Alpine.store('confirm').ask({
                title: 'Koreksi dengan AI',
                message: 'Koreksi jawaban ini dengan AI?',
                confirmLabel: 'Ya, Koreksi',
                variant: 'purple',
            });
            if (!ok) return;

            this.submitting = true;
            try {
                const response = await fetch(`{{ url('/ujian/attempt/answer') }}/${answerId}/ai`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (response.ok) {
                    alert(data.message || 'Berhasil dikoreksi dengan AI.');
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal mengoreksi dengan AI.');
                }
            } catch (error) {
                console.error('Error grading with AI:', error);
                alert('Terjadi kesalahan saat mengoreksi dengan AI.');
            } finally {
                this.submitting = false;
            }
        },

        async gradeAllWithAi() {
            if (this.submitting) return;
            const ok = await Alpine.store('confirm').ask({
                title: 'Koreksi Semua dengan AI',
                message: 'Koreksi semua jawaban dengan AI? Proses ini akan menimpa nilai yang sudah ada.',
                confirmLabel: 'Ya, Koreksi Semua',
                variant: 'purple',
            });
            if (!ok) return;

            this.submitting = true;
            try {
                const response = await fetch('{{ route('ujian.attempt.grade-all-ai', $attempt->uuid) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (response.ok) {
                    alert(data.message || 'Berhasil mengoreksi semua jawaban.');
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal mengoreksi dengan AI.');
                }
            } catch (error) {
                console.error('Error grading all with AI:', error);
                alert('Terjadi kesalahan saat mengoreksi dengan AI.');
            } finally {
                this.submitting = false;
            }
        },

        async submitGrading() {
            if (this.submitting) return;

            const hasEmptyScore = Object.values(this.scores).some(s => s.score === null || s.score === '');
            if (hasEmptyScore) {
                alert('Harap isi semua skor sebelum menyimpan.');
                return;
            }

            const hasInvalidScore = Object.values(this.scores).some(s => s.score < 0 || s.score > 100);
            if (hasInvalidScore) {
                alert('Skor harus antara 0-100.');
                return;
            }

            this.submitting = true;
            try {
                const response = await fetch('{{ route('ujian.rooms.attempts.grade', ['room' => $room->uuid, 'attempt' => $attempt->uuid]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ scores: this.scores })
                });
                const data = await response.json();
                if (response.ok) {
                    alert(data.message || 'Penilaian berhasil disimpan.');
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal menyimpan penilaian.');
                }
            } catch (error) {
                console.error('Error submitting grading:', error);
                alert('Terjadi kesalahan saat menyimpan penilaian.');
            } finally {
                this.submitting = false;
            }
        },

        printResult() {
            window.print();
        }
    };
}
</script>

<style>
@media print {
    body * { visibility: hidden; }
    .print-area, .print-area * { visibility: visible; }
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        max-width: 100%;
    }
    .no-print { display: none !important; }
}
</style>
@endpush
@endif