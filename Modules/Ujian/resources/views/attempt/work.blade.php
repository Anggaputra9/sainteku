@extends('ujian::layouts.exam')

@php
    /**
     * Variabel $room, $attempt, $questions, $existingAnswers di-passing dari
     * AttemptController::work(). Beberapa static analyzer (Intelephense)
     * memang menandai variabel view sebagai unassigned — kita kasih default
     * lokal supaya tooling tenang sekaligus halaman aman bila dirender ulang
     * tanpa data (misal dari preview).
     */
    /** @var \Modules\Ujian\Models\ExamRoom $room */
    /** @var \Modules\Ujian\Models\ExamAttempt $attempt */
    /** @var \Illuminate\Support\Collection $questions */
    /** @var \Illuminate\Support\Collection $existingAnswers */

    $existingAnswers = $existingAnswers ?? collect();
    $questions       = $questions ?? collect();

    $expiresIso = $attempt->expires_at?->toIso8601String();
    $policy     = $room->tab_switch_policy;
    $limit      = (int) $room->tab_switch_limit;

    // Susun data soal jadi array sederhana sehingga bisa diserialize ke JSON
    // dan dipakai di state Alpine. Pendekatan SPA-style: render satu soal aktif,
    // sisa soal disembunyikan tapi tetap di-mount supaya state textarea-nya
    // tidak hilang saat pindah-pindah nomor.
    $questionsData = $questions->map(function ($eq) use ($existingAnswers) {
        $q = $eq->question;
        return [
            'id'            => $q->id,
            'order_no'      => $eq->order_no,
            'weight'        => $eq->weight,
            'question_text' => $q->question_text,
            'image_url'     => $q->image_path ? asset('storage/'.$q->image_path) : null,
            'answer_text'   => $existingAnswers[$q->id]->answer_text ?? '',
            'is_answered'   => isset($existingAnswers[$q->id]) && trim((string) $existingAnswers[$q->id]->answer_text) !== '',
        ];
    })->values();
@endphp

@section('content')
<div class="flex flex-col min-h-screen"
    x-data="examWork({
        code: @js($room->room_code),
        saveUrl: @js(route('ujian.attempt.save-answer', ['code' => $room->room_code])),
        eventUrl: @js(route('ujian.attempt.event', ['code' => $room->room_code])),
        submitUrl: @js(route('ujian.attempt.submit', ['code' => $room->room_code])),
        finishedUrl: @js(route('ujian.attempt.finished', ['code' => $room->room_code])),
        expiresAt: @js($expiresIso),
        showRemainingTime: @js((bool) $room->show_remaining_time),
        policy: @js($policy),
        limit: {{ $limit }},
        initialCount: {{ (int) $attempt->tab_switch_count }},
        questions: @js($questionsData),
    })"
    x-init="init()"
    x-cloak>

    {{-- ============================================================
         HEADER MINIMAL: branding ujian + timer + status save + submit
         ============================================================ --}}
    <header class="sticky top-0 z-30 bg-white/95 dark:bg-[#0f172a]/95 backdrop-blur border-b border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="mx-auto max-w-6xl px-4 md:px-6 py-3">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <div class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest">
                        {{ $room->proposal->exam_type ?? 'Ujian' }} · Mode Pengerjaan
                    </div>
                    <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white truncate">{{ $room->title }}</h2>
                    <div class="text-xs text-gray-500 truncate">
                        {{ $room->proposal->course->course_name ?? '-' }}
                        · Kode <b class="font-mono">{{ $room->room_code }}</b>
                        · {{ Auth::user()->name }}
                    </div>
                </div>

                <div class="flex w-full items-stretch justify-end gap-2 sm:w-auto sm:items-center sm:gap-3">
                    @if($room->show_remaining_time)
                        <div class="inline-flex h-11 min-w-[6.5rem] items-center justify-center gap-2 rounded-xl border bg-white px-4 dark:bg-[#1e293b]"
                            :class="timeWarn ? 'border-red-300 ring-2 ring-red-200 dark:border-red-400' : 'border-gray-200 dark:border-gray-700'"
                            title="Sisa waktu">
                            <i class="fa-solid fa-clock text-sm shrink-0"
                                :class="timeWarn ? 'text-red-500' : 'text-indigo-500'"></i>
                            <span class="text-sm font-bold font-mono tabular-nums leading-none sm:text-base"
                                :class="timeWarn ? 'text-red-600' : 'text-indigo-700 dark:text-indigo-300'"
                                x-text="timeLeft"></span>
                        </div>
                    @endif

                    <div class="hidden sm:flex h-11 min-w-[7rem] items-center text-xs text-gray-500">
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-cloud-arrow-up text-emerald-500"></i>
                            <span x-text="saveStatus"></span>
                        </div>
                    </div>

                    <button type="button" @click="confirmSubmit()" aria-label="Submit jawaban"
                        class="inline-flex h-11 min-w-[6.5rem] shrink-0 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 text-sm font-bold text-white shadow-sm hover:bg-emerald-700 sm:text-base">
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                        <span>Submit</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Banner pelanggaran --}}
        <div x-show="warningVisible" x-transition x-cloak
            class="border-t border-red-300 bg-red-50 px-4 py-2 text-sm font-bold text-red-700 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span x-text="warningMessage"></span>
        </div>
    </header>

    {{-- ============================================================
         BODY: 1 soal aktif + sidebar navigasi nomor
         ============================================================ --}}
    <main class="flex-1 mx-auto w-full max-w-6xl px-4 md:px-6 py-5">

        {{-- Policy info bar --}}
        <div class="mb-4 rounded-xl bg-amber-50 border border-amber-200 px-4 py-2 text-xs text-amber-800 flex items-center gap-2">
            <i class="fa-solid fa-shield-halved"></i>
            <span>
                Kebijakan tab/fokus:
                <b>
                    @if($policy==='unlimited') Tanpa batas (hanya dicatat)
                    @elseif($policy==='strict') Tanpa toleransi — pelanggaran pertama auto-submit
                    @else Maks {{ $limit }}x pelanggaran sebelum auto-submit
                    @endif
                </b>
                · Pelanggaran tercatat: <b x-text="violationCount"></b>x
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_240px] gap-5">

            {{-- ====== SOAL AKTIF ====== --}}
            <section class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 dark:border-gray-700 dark:bg-[#1e293b]">
                <div class="flex items-center justify-between gap-3 pb-3 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <div class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest">
                            Soal <span x-text="currentIndex + 1"></span>
                            <span class="text-gray-400">/ <span x-text="questions.length"></span></span>
                        </div>
                        <div class="text-xs text-gray-500 mt-0.5">
                            Bobot <b x-text="current()?.weight"></b>%
                            ·
                            <span class="font-semibold"
                                :class="current()?.is_answered ? 'text-emerald-600' : 'text-amber-600'"
                                x-text="current()?.is_answered ? 'Sudah dijawab' : 'Belum dijawab'"></span>
                        </div>
                    </div>
                    <div class="text-[11px] text-gray-500 hidden sm:block">
                        <i class="fa-solid fa-keyboard"></i> Tekan
                        <kbd class="rounded bg-gray-100 px-1 py-0.5 border text-gray-700">←</kbd>
                        /
                        <kbd class="rounded bg-gray-100 px-1 py-0.5 border text-gray-700">→</kbd>
                        untuk pindah
                    </div>
                </div>

                {{-- Render semua soal sebagai panel; hanya yang aktif yang terlihat. --}}
                <template x-for="(q, idx) in questions" :key="q.id">
                    <div x-show="idx === currentIndex" class="pt-4">
                        <div class="prose dark:prose-invert max-w-none text-sm sm:text-base text-gray-800 dark:text-gray-200 whitespace-pre-line"
                            x-text="q.question_text"></div>

                        <template x-if="q.image_url">
                            <img :src="q.image_url" alt=""
                                class="mt-3 max-h-72 rounded-lg border border-gray-200 dark:border-gray-700">
                        </template>

                        <label class="block text-[11px] font-bold text-gray-500 uppercase mt-5 mb-2">
                            Jawaban Anda
                        </label>
                        <textarea
                            rows="8"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 text-sm dark:bg-[#0f172a] dark:border-gray-600 dark:text-white"
                            placeholder="Tuliskan jawaban di sini..."
                            x-model="q.answer_text"
                            @input.debounce.700ms="saveAnswer(q.id, q.answer_text)"></textarea>
                    </div>
                </template>

                {{-- Tombol prev / next / submit — compact sampai layout desktop (lg) --}}
                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-center gap-2 lg:justify-between lg:gap-3">
                        <button type="button" @click="prev()" :disabled="currentIndex === 0"
                            aria-label="Soal sebelumnya"
                            class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-600 dark:bg-[#0f172a] dark:text-gray-200 lg:h-auto lg:w-auto lg:gap-2 lg:px-4 lg:py-2">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span class="hidden lg:inline">Sebelumnya</span>
                        </button>

                        <div class="min-w-[4.5rem] text-center text-xs text-gray-500 tabular-nums lg:min-w-0">
                            <span class="lg:hidden">
                                <span x-text="currentIndex + 1"></span>/<span x-text="questions.length"></span>
                            </span>
                            <span class="hidden lg:inline">
                                <span x-text="answeredCount()"></span> dari <span x-text="questions.length"></span> dijawab
                            </span>
                        </div>

                        <template x-if="currentIndex < questions.length - 1">
                            <button type="button" @click="next()" aria-label="Soal berikutnya"
                                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 lg:h-auto lg:w-auto lg:gap-2 lg:px-5 lg:py-2">
                                <span class="hidden lg:inline">Berikutnya</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </template>
                        <template x-if="currentIndex === questions.length - 1">
                            <button type="button" @click="confirmSubmit()" aria-label="Submit jawaban"
                                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-sm font-bold text-white hover:bg-emerald-700 lg:h-auto lg:w-auto lg:gap-2 lg:px-5 lg:py-2">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span class="hidden lg:inline">Submit</span>
                            </button>
                        </template>
                    </div>
                </div>
            </section>

            {{-- ====== SIDEBAR NAVIGASI ====== --}}
            <aside class="lg:sticky lg:top-28 self-start">
                <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-xs font-bold text-gray-500 uppercase">Navigasi Soal</div>
                        <div class="text-[11px] text-gray-400 tabular-nums">
                            <span x-text="answeredCount()"></span>/<span x-text="questions.length"></span>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-center gap-2 lg:grid lg:grid-cols-5 lg:gap-2">
                        <template x-for="(q, idx) in questions" :key="`nav-${q.id}`">
                            <button type="button" @click="goTo(idx)"
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-xs font-bold border transition lg:aspect-square lg:h-auto lg:w-auto lg:text-sm"
                                :class="idx === currentIndex
                                    ? 'bg-indigo-600 text-white border-indigo-600 ring-2 ring-indigo-300'
                                    : (q.is_answered
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-300 hover:bg-emerald-100'
                                        : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100 dark:bg-[#0f172a] dark:text-gray-300 dark:border-gray-600')"
                                x-text="idx + 1"></button>
                        </template>
                    </div>

                    <div class="mt-4 space-y-1.5 text-[11px] text-gray-500">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded bg-indigo-600 border border-indigo-700"></span>
                            Soal saat ini
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded bg-emerald-100 border border-emerald-300"></span>
                            Sudah dijawab
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded bg-gray-100 border border-gray-300"></span>
                            Belum dijawab
                        </div>
                    </div>

                    <button type="button" @click="confirmSubmit()"
                        class="mt-4 w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700">
                        <i class="fa-solid fa-paper-plane"></i> Submit Sekarang
                    </button>
                </div>
            </aside>
        </div>
    </main>

    {{-- Hidden submit form (fallback / submit manual) --}}
    <form id="submit-form" method="POST" action="{{ route('ujian.attempt.submit', ['code' => $room->room_code]) }}" class="hidden">
        @csrf
    </form>

    <template x-teleport="#modal-root">

        <div x-show="confirmOpen"
        class="app-modal-overlay fixed inset-0 z-[999991] flex items-center justify-center p-3 sm:p-6 overflow-y-auto backdrop-blur-sm bg-gray-900/40"
        x-transition x-cloak>
        <div @click.away="!confirmSubmitting && (confirmOpen = false)"
            class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 overflow-hidden">
            <div class="border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100" x-text="confirmTitle"></h3>
            </div>
            <div class="p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a]">
                <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line" x-text="confirmMessage"></p>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="confirmOpen = false" :disabled="confirmSubmitting"
                        class="rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 disabled:opacity-60 dark:bg-gray-700 dark:text-gray-200">Batal</button>
                    <button type="button" @click="runSubmitConfirmation()" :disabled="confirmSubmitting"
                        class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 disabled:opacity-60">
                        <span x-text="confirmSubmitting ? 'Memproses…' : 'Ya, submit jawaban'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    </template>
</div>

<script>
function examWork(opts) {
    return {
        ...opts,

        // ===== state =====
        currentIndex: 0,
        timeLeft: '--:--',
        timeWarn: false,
        saveStatus: 'Siap',
        violationCount: opts.initialCount ?? 0,
        warningVisible: false,
        warningMessage: '',
        confirmOpen: false,
        confirmSubmitting: false,
        confirmTitle: 'Submit Jawaban Ujian',
        confirmMessage: '',

        // ===== internal =====
        _csrf: document.querySelector('meta[name="csrf-token"]')?.content,
        _timerHandle: null,
        _expiresMs: null,

        init() {
            this._expiresMs = this.expiresAt ? new Date(this.expiresAt).getTime() : null;
            if (this._expiresMs) {
                this._tick();
                this._timerHandle = setInterval(() => this._tick(), 1000);
            }

            // Anti tab-switch listeners
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) this.fireEvent('tab_hidden');
            });
            window.addEventListener('blur', () => this.fireEvent('focus_lost'));

            // Block context menu & keyboard shortcut umum (best-effort)
            document.addEventListener('contextmenu', e => e.preventDefault());
            document.addEventListener('keydown', e => {
                if ((e.ctrlKey || e.metaKey) && ['p','s','u'].includes((e.key || '').toLowerCase())) {
                    e.preventDefault();
                }
                if (e.key === 'F12') e.preventDefault();

                // Navigasi nomor soal pakai panah kiri/kanan,
                // tapi jangan ganggu saat user lagi ngetik di textarea/input.
                const target = e.target;
                const tag = (target?.tagName || '').toLowerCase();
                if (tag === 'textarea' || tag === 'input') return;
                if (e.key === 'ArrowRight') this.next();
                if (e.key === 'ArrowLeft')  this.prev();
            });
        },

        // ===== navigasi soal =====
        current() { return this.questions[this.currentIndex]; },
        goTo(idx) {
            if (idx < 0 || idx >= this.questions.length) return;
            this.currentIndex = idx;
            // Scroll ke atas soal supaya pengguna mobile/tablet langsung lihat
            // header soal bagian kiri ketika berpindah.
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        next() { this.goTo(this.currentIndex + 1); },
        prev() { this.goTo(this.currentIndex - 1); },
        answeredCount() {
            return this.questions.reduce((acc, q) => acc + (q.is_answered ? 1 : 0), 0);
        },

        // ===== timer =====
        _tick() {
            const now = Date.now();
            const diff = Math.max(0, this._expiresMs - now);
            const total = Math.floor(diff / 1000);
            const h = String(Math.floor(total/3600)).padStart(2,'0');
            const m = String(Math.floor((total%3600)/60)).padStart(2,'0');
            const s = String(total%60).padStart(2,'0');
            this.timeLeft = (h !== '00') ? `${h}:${m}:${s}` : `${m}:${s}`;
            this.timeWarn = total <= 60;
            if (total <= 0) {
                clearInterval(this._timerHandle);
                this.timeLeft = '00:00';
                window.location.href = this.finishedUrl;
            }
        },

        // ===== save jawaban =====
        async saveAnswer(questionId, text) {
            this.saveStatus = 'Menyimpan…';
            try {
                const res = await fetch(this.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this._csrf,
                    },
                    body: JSON.stringify({ question_id: questionId, answer_text: text }),
                });
                const data = await res.json();
                if (data.redirect) { window.location.href = data.redirect; return; }
                if (data.ok) {
                    this.saveStatus = 'Tersimpan ' + new Date().toLocaleTimeString();
                    const target = this.questions.find(q => q.id === questionId);
                    if (target) target.is_answered = (text || '').trim().length > 0;
                } else {
                    this.saveStatus = 'Gagal menyimpan';
                }
            } catch (e) {
                this.saveStatus = 'Offline / gagal';
            }
        },

        async fireEvent(eventType, payload = null) {
            try {
                const res = await fetch(this.eventUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this._csrf,
                    },
                    body: JSON.stringify({ event_type: eventType, payload }),
                });
                const data = await res.json();

                if (typeof data.tab_switch_count === 'number') {
                    this.violationCount = data.tab_switch_count;
                    this.warningMessage = `Pelanggaran tab/fokus terdeteksi (${this.violationCount}x). ${this._policyMessage()}`;
                    this.warningVisible = true;
                    setTimeout(() => this.warningVisible = false, 8000);
                }
                if (data.auto_submitted && data.redirect) {
                    window.location.href = data.redirect;
                }
            } catch (e) { /* ignore */ }
        },

        _policyMessage() {
            if (this.policy === 'strict') return 'Sistem akan auto-submit ujian Anda.';
            if (this.policy === 'limited') {
                const remaining = Math.max(0, this.limit - this.violationCount);
                return remaining > 0
                    ? `Sisa toleransi: ${remaining}x sebelum auto-submit.`
                    : 'Anda sudah melebihi batas, ujian akan disubmit.';
            }
            return 'Pelanggaran dicatat oleh sistem.';
        },

        confirmSubmit() {
            const unanswered = this.questions.length - this.answeredCount();
            this.confirmTitle = 'Submit Jawaban Ujian';
            this.confirmMessage = unanswered > 0
                ? `Masih ada ${unanswered} soal belum dijawab. Setelah submit, jawaban tidak bisa diubah lagi.`
                : 'Yakin submit jawaban? Setelah submit, Anda tidak bisa mengubah jawaban lagi.';
            this.confirmOpen = true;
        },

        runSubmitConfirmation() {
            this.confirmSubmitting = true;
            document.getElementById('submit-form').submit();
        },
    }
}
</script>
@endsection