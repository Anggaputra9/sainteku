@extends('layouts.app')

@section('content')
    {{--
        Halaman Hasil Ujian per attempt — diakses dengan UUID, bukan id.
        Mahasiswa hanya bisa lihat hasilnya sendiri; dosen pembuat room
        dan administrator bisa lihat hasil siapa pun.
    --}}
    @php
        $room = $attempt->room;
        $answered = $attempt->answers->where('is_answered', true)->count();
        $statusColors = match($attempt->status) {
            'SUBMITTED'                => 'bg-green-100 text-green-800 border-green-200',
            'AUTO_SUBMITTED_TIME'      => 'bg-amber-100 text-amber-800 border-amber-200',
            'AUTO_SUBMITTED_VIOLATION' => 'bg-red-100 text-red-800 border-red-200',
            'ONGOING'                  => 'bg-blue-100 text-blue-800 border-blue-200',
            default                    => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    @endphp

    <div class="max-w-4xl mx-auto py-6 space-y-6">

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-check text-indigo-500"></i> Hasil Ujian
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $room->title }}</p>
                </div>
                <span class="inline-flex rounded-md px-3 py-1.5 text-xs font-bold uppercase tracking-wider border {{ $statusColors }}">
                    {{ $attempt->statusLabel() }}
                </span>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Mahasiswa</div>
                <div class="mt-1 font-bold text-gray-900 dark:text-white text-sm">{{ $attempt->user?->name ?? '—' }}</div>
                <div class="text-xs text-gray-500">{{ $attempt->user?->identity_id }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Soal Dijawab</div>
                <div class="mt-1 font-bold text-gray-900 dark:text-white text-xl tabular-nums">{{ $answered }}/{{ $totalQuestions }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Pelanggaran</div>
                <div class="mt-1 font-bold text-gray-900 dark:text-white text-xl tabular-nums">{{ $attempt->tab_switch_count }}x</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Skor</div>
                <div class="mt-1 font-bold text-gray-900 dark:text-white text-xl tabular-nums">
                    @if($attempt->score !== null)
                        {{ $attempt->score }}
                    @else
                        <span class="text-sm italic text-gray-400">Belum dinilai</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Waktu --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b] grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
            <div>
                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Mulai</div>
                <div class="text-gray-900 dark:text-white">{{ optional($attempt->started_at)->format('d M Y H:i:s') ?? '—' }}</div>
            </div>
            <div>
                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Submit</div>
                <div class="text-gray-900 dark:text-white">{{ optional($attempt->submitted_at)->format('d M Y H:i:s') ?? '—' }}</div>
            </div>
            <div>
                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Aktivitas Terakhir</div>
                <div class="text-gray-900 dark:text-white">{{ optional($attempt->last_activity_at)->format('d M Y H:i:s') ?? '—' }}</div>
            </div>
        </div>

        {{-- Daftar jawaban (visible untuk dosen, atau mahasiswa jika sudah selesai) --}}
        @if($isLecturer || $attempt->isFinished())
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden dark:border-gray-700 dark:bg-[#1e293b]"
                 x-data="gradingApp()" x-init="init()">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-gray-800/40 dark:border-gray-700 flex items-center justify-between">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-indigo-500"></i> Jawaban
                    </h4>
                    <div class="flex gap-2">
                        @if($isLecturer && $attempt->isFinished())
                            <button type="button" @click="gradeAllWithAi()" :disabled="submitting"
                                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-indigo-700 disabled:opacity-60">
                                <i class="fa-solid fa-robot"></i> Koreksi Semua dengan AI
                            </button>
                            <button type="button" @click="toggleGradingMode()"
                                class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-bold transition"
                                :class="gradingMode ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-blue-600 text-white hover:bg-blue-700'">
                                <i class="fa-solid" :class="gradingMode ? 'fa-eye' : 'fa-pen'"></i>
                                <span x-text="gradingMode ? 'Mode Lihat' : 'Mode Koreksi'"></span>
                            </button>
                        @endif
                        @if($attempt->isFinished() && $attempt->answers->whereNotNull('score')->count() === $totalQuestions)
                            <button type="button" @click="printResult()"
                                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-green-700">
                                <i class="fa-solid fa-print"></i> Print Hasil
                            </button>
                        @endif
                    </div>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($room->proposal->examQuestions->sortBy('order_no') as $eq)
                        @php
                            $ans = $attempt->answers->firstWhere('question_id', $eq->question_id);
                        @endphp
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                    Soal {{ $eq->order_no }} (Bobot: {{ $eq->weight }}%)
                                </div>
                                @if($ans && $ans->score !== null)
                                    <div class="text-xs font-bold text-emerald-600">
                                        Skor: {{ $ans->score }}
                                    </div>
                                @endif
                            </div>
                            <div class="text-sm text-gray-900 dark:text-white">{!! nl2br(e($eq->question?->question_text ?? '—')) !!}</div>

                            <div class="mt-3 rounded-lg bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 p-3">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Jawaban Mahasiswa</div>
                                @if($ans && trim($ans->answer_text ?? '') !== '')
                                    <div class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $ans->answer_text }}</div>
                                @else
                                    <span class="text-xs italic text-gray-400">Tidak dijawab</span>
                                @endif
                            </div>

                            @if($isLecturer && $attempt->isFinished())
                                <div x-show="gradingMode" x-transition class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Skor (0-100)</label>
                                        <input type="number" min="0" max="100" step="0.01"
                                            x-model="scores[{{ $eq->question_id }}].score"
                                            @input="if($event.target.value > 100) $event.target.value = 100; if($event.target.value < 0) $event.target.value = 0;"
                                            class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white"
                                            placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Koreksi AI</label>
                                        <button type="button" @click="gradeWithAi({{ $eq->question_id }}, {{ $ans ? $ans->id : 'null' }})" :disabled="submitting"
                                            class="w-full rounded-lg bg-indigo-600 px-3 py-2 text-sm font-bold text-white hover:bg-indigo-700 disabled:opacity-60">
                                            <i class="fa-solid fa-robot"></i> Koreksi dengan AI
                                        </button>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Catatan Koreksi (Opsional)</label>
                                        <textarea rows="2"
                                            x-model="scores[{{ $eq->question_id }}].grader_note"
                                            class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white"
                                            placeholder="Catatan untuk mahasiswa..."></textarea>
                                    </div>
                                </div>

                                @if($ans && ($ans->grader_note || $ans->ai_feedback))
                                    <div x-show="!gradingMode" class="mt-3 rounded-lg bg-blue-50 border border-blue-200 p-3 dark:bg-blue-900/20 dark:border-blue-900/40">
                                        <div class="text-[10px] font-bold uppercase tracking-widest text-blue-700 dark:text-blue-300 mb-1">
                                            @if($ans->grading_method === 'ai')
                                                <i class="fa-solid fa-robot"></i> Feedback AI
                                            @else
                                                Catatan Dosen
                                            @endif
                                        </div>
                                        <div class="text-xs text-blue-800 dark:text-blue-200">
                                            {{ $ans->grading_method === 'ai' ? $ans->ai_feedback : $ans->grader_note }}
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($isLecturer && $attempt->isFinished())
                    <div x-show="gradingMode" x-transition class="px-4 py-3 bg-gray-50 border-t border-gray-200 dark:bg-gray-800/40 dark:border-gray-700 flex justify-end gap-2">
                        <button type="button" @click="toggleGradingMode()"
                            class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">
                            Batal
                        </button>
                        <button type="button" @click="submitGrading()" :disabled="submitting"
                            class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-bold text-white hover:bg-emerald-700 disabled:opacity-60">
                            <span x-text="submitting ? 'Menyimpan...' : 'Simpan Penilaian'"></span>
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <div class="flex justify-end">
            @if($isLecturer)
                <a href="{{ route('ujian.rooms.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Ruang Ujian
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">
                    <i class="fa-solid fa-house"></i> Kembali ke Dashboard
                </a>
            @endif
        </div>
    </div>
@endsection

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
                    grader_note: '{{ $ans && $ans->grader_note ? addslashes($ans->grader_note) : '' }}'
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

            if (!confirm('Koreksi jawaban ini dengan AI?')) return;

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

            if (!confirm('Koreksi semua jawaban dengan AI? Proses ini akan menimpa nilai yang sudah ada.')) return;

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
                    body: JSON.stringify({
                        scores: this.scores
                    })
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
    body * {
        visibility: hidden;
    }
    .max-w-4xl, .max-w-4xl * {
        visibility: visible;
    }
    .max-w-4xl {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    button, .no-print {
        display: none !important;
    }
}
</style>
@endpush
