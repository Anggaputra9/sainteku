<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

@php
    $inputClass = 'w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 placeholder:text-gray-400 dark:border-gray-600 dark:bg-[#0f172a] dark:text-white dark:placeholder:text-gray-500';
    $datetimeClass = 'datetime-input';
    $toggleTrackClass = "relative w-11 h-6 bg-gray-300 rounded-full outline-none dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-gray-500 peer-checked:bg-indigo-600";
    $settingRowClass = 'setting-row px-5 py-3.5 grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_220px] gap-3 md:gap-6 items-center transition-colors duration-150';
    $uniqueFakultas = collect($proposals)->filter(fn ($p) => ! empty($p['fakultas_id']))->unique('fakultas_id')->values();
    $uniqueProdis = collect($proposals)->filter(fn ($p) => ! empty($p['prodi_id']))->unique('prodi_id')->values();
    $uniqueCourses = collect($proposals)->unique('course_id')->values();
    $btnGray = 'inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 shadow-sm hover:bg-gray-300 focus:ring-4 focus:ring-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 sm:gap-2 sm:px-6 sm:py-2.5 sm:text-sm transition-all';
    $btnRed = 'inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-red-600 px-3 py-2 text-xs font-bold text-white shadow-md shadow-red-600/20 hover:bg-red-700 hover:shadow-lg focus:ring-4 focus:ring-red-500/30 sm:gap-2 sm:px-6 sm:py-2.5 sm:text-sm transition-all';
    $btnBlue = 'inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 hover:shadow-lg focus:ring-4 focus:ring-blue-500/30 sm:gap-2 sm:px-8 sm:py-2.5 sm:text-sm transition-all';
    $btnPurple = 'inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-purple-600 px-3 py-2 text-xs font-bold text-white shadow-md shadow-purple-600/20 hover:bg-purple-700 hover:shadow-lg focus:ring-4 focus:ring-purple-500/30 sm:gap-2 sm:px-6 sm:py-2.5 sm:text-sm transition-all';
    $btnEmerald = 'inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-700 hover:shadow-lg focus:ring-4 focus:ring-emerald-500/30 sm:gap-2 sm:px-6 sm:py-2.5 sm:text-sm transition-all';
    $btnAmber = 'inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-amber-500 px-3 py-2 text-xs font-bold text-white shadow-md shadow-amber-500/20 hover:bg-amber-600 hover:shadow-lg focus:ring-4 focus:ring-amber-500/30 sm:gap-2 sm:px-6 sm:py-2.5 sm:text-sm transition-all';
    $btnGreen = 'inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-green-600 px-3 py-2 text-xs font-bold text-white shadow-md shadow-green-600/20 hover:bg-green-700 hover:shadow-lg focus:ring-4 focus:ring-green-500/30 sm:gap-2 sm:px-6 sm:py-2.5 sm:text-sm transition-all';
@endphp

<template x-teleport="#modal-root">
<div x-data="openRoomDetailModal()" @open-detail-modal.window="handleOpenDetail($event)"
    @rooms-data-changed.window="if (openDetail && roomUuid) reloadDetail()" x-show="openDetail"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center p-3 sm:p-6 overflow-y-auto backdrop-blur-sm bg-gray-900/40 dark:bg-black/60"
    x-transition x-cloak>

    <div @click.away="!editMode && closeDetail()"
        class="relative w-full rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 flex flex-col max-h-[90dvh] sm:max-h-[95vh] overflow-hidden transition-all"
        :class="editMode ? 'max-w-3xl' : 'max-w-5xl'">

        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
            <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                    <i class="fa-solid fa-chalkboard-user text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                </div>
                <div class="min-w-0 leading-tight">
                    <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl"
                        x-text="editMode ? 'Edit Ruang Ujian' : 'Detail Ruang Ujian'"></h3>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm"
                        x-text="editMode ? 'Perbarui konfigurasi ruang ujian' : (detail.room?.title || roomTitle)"></p>
                </div>
            </div>
            <div class="shrink-0 w-8 sm:w-9"></div>
        </div>

        {{-- MODE LIHAT --}}
        <div x-show="!editMode" class="flex flex-col flex-1 overflow-hidden">
            <div class="flex-1 overflow-y-auto custom-scrollbar p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a]">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden dark:border-gray-700 dark:bg-[#1e293b] flex flex-col">
                        <div class="px-4 py-3 border-b border-indigo-100 bg-gradient-to-r from-indigo-50 to-violet-50 dark:from-indigo-950/50 dark:to-violet-950/40 dark:border-indigo-900/40">
                            <div class="flex items-center justify-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-600 text-white shadow-sm dark:bg-indigo-500">
                                    <i class="fa-solid fa-key text-xs"></i>
                                </span>
                                <div class="text-left">
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Kode Akses</div>
                                    <div class="text-[11px] text-gray-500 dark:text-gray-400">Untuk mahasiswa masuk ujian</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-5 flex flex-col items-center gap-4 flex-1">
                            <div class="w-full">
                                <div class="flex justify-center">
                                    <div class="rounded-2xl bg-white p-3 shadow-md ring-1 ring-gray-200 dark:bg-white dark:ring-gray-300">
                                        <div x-ref="qrCanvas" class="leading-none"></div>
                                    </div>
                                </div>
                                <p class="text-[11px] text-center text-gray-500 dark:text-gray-400 mt-3">
                                    Scan QR dengan kamera HP
                                </p>
                            </div>

                            <div class="w-full border-t border-gray-100 dark:border-gray-700 pt-4">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 text-center mb-3">atau masukkan kode</div>
                                <div class="rounded-xl border border-dashed border-indigo-200 bg-indigo-50/60 px-3 py-4 dark:border-indigo-800/60 dark:bg-indigo-950/30">
                                    <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                                        <template x-for="(ch, i) in accessCodeChars" :key="i">
                                            <span class="access-code-char inline-flex h-10 w-9 sm:h-11 sm:w-10 items-center justify-center rounded-lg border border-indigo-200 bg-white font-mono text-lg sm:text-xl font-bold text-indigo-700 shadow-sm dark:border-indigo-700 dark:bg-[#0f172a] dark:text-indigo-300"
                                                x-text="ch"></span>
                                        </template>
                                        <span x-show="accessCodeChars.length === 0" class="text-sm text-gray-400 dark:text-gray-500">—</span>
                                    </div>
                                </div>
                            </div>

                            <button type="button" @click="copyJoinLink()"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold text-white shadow-md transition-all sm:px-6 sm:py-2.5 sm:text-sm focus:ring-4 mt-auto"
                                :class="linkCopied
                                    ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/20 focus:ring-emerald-500/30'
                                    : 'bg-blue-600 hover:bg-blue-700 shadow-blue-600/20 focus:ring-blue-500/30'">
                                <i class="fas" :class="linkCopied ? 'fa-check' : 'fa-link'"></i>
                                <span x-text="linkCopied ? 'Link disalin!' : 'Salin Link'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="md:col-span-2 space-y-3">
                        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b] overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fa-solid fa-book text-indigo-500 dark:text-indigo-400"></i> Mata Kuliah & Paket Soal
                                </h4>
                            </div>
                            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Fakultas</div>
                                    <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white" x-text="detail.proposal_context?.fakultas_name || '-'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Program Studi</div>
                                    <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white" x-text="detail.proposal_context?.prodi_name || '-'"></div>
                                </div>
                                <div class="sm:col-span-2">
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Mata Kuliah</div>
                                    <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white" x-text="detail.proposal_context?.course_name || '-'"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Paket Soal</div>
                                    <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white" x-text="proposalShortLabel(detail.proposal_context)"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Periode</div>
                                    <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white" x-text="detail.proposal_context?.period_name || '-'"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Status</div>
                                <div class="mt-1">
                                    <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border"
                                        :class="detail.room?.status === 'PUBLISHED' ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800'
                                            : detail.room?.status === 'CLOSED' ? 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800'
                                            : 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-700/50 dark:text-gray-300 dark:border-gray-600'"
                                        x-text="statusLabel(detail.room?.status)"></span>
                                </div>
                            </div>
                            <div class="rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Durasi</div>
                                <div class="mt-1 font-bold text-gray-900 dark:text-white">
                                    <span x-text="detail.room?.duration_minutes"></span> menit
                                </div>
                            </div>
                            <div class="rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Jadwal Mulai</div>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white" x-text="detail.room?.start_at_human || detail.room?.start_at"></div>
                            </div>
                            <div class="rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Selesai</div>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white" x-text="detail.room?.end_at_human || detail.room?.end_at"></div>
                            </div>
                            <div class="rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-[#1e293b] sm:col-span-2">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Kebijakan Tab Switch</div>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white" x-text="detail.room?.tab_switch_label"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-gray-800/40 dark:border-gray-700 flex flex-wrap items-center justify-between gap-2">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-chart-line text-indigo-500"></i> Monitor Live
                            <span x-show="livePolling" class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase text-emerald-700 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live
                            </span>
                        </h4>
                        <div class="flex items-center gap-2 text-[11px] text-gray-500">
                            <span x-show="lastLiveUpdate">Update <span x-text="lastLiveUpdate"></span></span>
                            <button type="button" @click="reloadDetail()" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                <i class="fa-solid fa-arrows-rotate" :class="liveUpdating && 'fa-spin'"></i> Refresh
                            </button>
                        </div>
                    </div>

                    <div class="p-4 grid grid-cols-2 sm:grid-cols-4 gap-3 border-b border-gray-100 dark:border-gray-700">
                        <div class="rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#0f172a]">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Peserta</div>
                            <div class="mt-1 text-xl font-black text-gray-900 dark:text-white tabular-nums" x-text="detail.summary?.total_participants || 0"></div>
                            <div class="text-[11px] text-gray-500 mt-0.5">
                                <span class="text-amber-600" x-text="(detail.summary?.ongoing || 0) + ' aktif'"></span>
                                · <span x-text="(detail.summary?.finished || 0) + ' selesai'"></span>
                            </div>
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#0f172a]">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Soal Dijawab</div>
                            <div class="mt-1 text-xl font-black text-gray-900 dark:text-white tabular-nums">
                                <span x-text="detail.summary?.total_answered || 0"></span><span class="text-sm text-gray-400">/<span x-text="detail.summary?.max_answerable || 0"></span></span>
                            </div>
                            <div class="text-[11px] text-gray-500 mt-0.5"><span x-text="detail.summary?.total_questions || detail.total_questions || 0"></span> soal / peserta</div>
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#0f172a]">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Sudah Dinilai</div>
                            <div class="mt-1 text-xl font-black text-emerald-600 tabular-nums" x-text="detail.summary?.graded || 0"></div>
                            <div class="text-[11px] text-gray-500 mt-0.5">
                                <span x-text="(detail.summary?.grading_pending || 0) + ' menunggu'"></span>
                            </div>
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#0f172a]">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Koreksi AI</div>
                            <div class="mt-1 text-sm font-bold"
                                :class="detail.summary?.auto_grading_enabled ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500'"
                                x-text="detail.summary?.auto_grading_enabled ? 'Otomatis aktif' : 'Manual'"></div>
                            <div class="text-[11px] mt-0.5"
                                :class="detail.summary?.grading_active ? 'text-purple-600 font-semibold' : 'text-gray-500'"
                                x-text="detail.summary?.grading_active ? 'Sedang mengoreksi…' : 'Siap'"></div>
                        </div>
                    </div>

                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between gap-2">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-users text-indigo-500"></i>
                            Daftar Peserta
                            <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-[11px] font-bold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300"
                                x-text="detail.attempts?.length || 0"></span>
                        </h4>
                    </div>

                    <template x-if="!detail.attempts || detail.attempts.length === 0">
                        <div class="px-6 py-12 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800">
                                <i class="fa-solid fa-user-group text-lg"></i>
                            </div>
                            <p class="mt-3 text-sm font-semibold text-gray-600 dark:text-gray-300">Belum ada peserta</p>
                            <p class="mt-1 text-xs text-gray-500">Bagikan kode atau QR ruang ujian ke mahasiswa.</p>
                        </div>
                    </template>

                    <template x-if="detail.attempts && detail.attempts.length > 0">
                        <div class="overflow-x-auto">
                            <table class="participant-table w-full min-w-[720px] text-left text-sm text-gray-600 dark:text-gray-400">
                                <thead class="text-[10px] font-bold uppercase tracking-widest bg-gray-50 text-gray-700 border-b border-gray-200 dark:bg-gray-700/50 dark:text-gray-300 dark:border-gray-600">
                                    <tr>
                                        <th class="px-4 py-2.5 text-center w-10">#</th>
                                        <th class="px-4 py-2.5">Peserta</th>
                                        <th class="px-4 py-2.5 w-36">Jawaban</th>
                                        <th class="px-4 py-2.5">Status</th>
                                        <th class="px-4 py-2.5 text-center w-16"
                                            x-show="detail.room?.auto_grading_enabled">Skor</th>
                                        <th class="px-4 py-2.5 text-right w-28">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/80">
                                    <template x-for="(a, idx) in detail.attempts" :key="a.uuid">
                                        <tr class="participant-row transition-colors hover:bg-indigo-50/50 dark:hover:bg-indigo-950/20">
                                            <td class="px-4 py-3 text-center text-xs font-bold tabular-nums text-gray-400" x-text="idx + 1"></td>
                                            <td class="px-4 py-3">
                                                <div class="flex min-w-0 items-center gap-2.5">
                                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs font-black border"
                                                        :class="participantAvatarClass(a.user_name)"
                                                        x-text="participantInitial(a.user_name)"></div>
                                                    <div class="min-w-0">
                                                        <div class="truncate font-bold text-gray-900 dark:text-white" x-text="a.user_name || '—'"></div>
                                                        <div class="truncate text-[11px] text-gray-500 dark:text-gray-400 font-mono" x-text="a.user_identity || '—'"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-between gap-2 text-[11px] font-semibold text-gray-500 mb-1">
                                                    <span class="tabular-nums text-gray-700 dark:text-gray-300" x-text="`${a.answered}/${a.total_questions}`"></span>
                                                    <span class="tabular-nums" x-text="`${participantAnswerPercent(a)}%`"></span>
                                                </div>
                                                <div class="h-1.5 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                                                    <div class="h-full rounded-full transition-all duration-500"
                                                        :class="participantProgressClass(a)"
                                                        :style="`width: ${participantAnswerPercent(a)}%`"></div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 min-w-[9rem]">
                                                <div class="flex flex-col gap-1.5 min-w-0">
                                                    <span class="participant-pill max-w-full min-w-0 uppercase tracking-wide truncate"
                                                        :class="participantStatusClass(a)"
                                                        :title="a.status_label"
                                                        x-text="a.status_label"></span>
                                                    <div class="flex flex-wrap items-center gap-1.5 min-w-0">
                                                        <template x-if="detail.room?.auto_grading_enabled">
                                                            <span class="participant-pill max-w-full min-w-0"
                                                                :class="participantGradingClass(a)"
                                                                :title="a.grading_label || '—'">
                                                                <i class="fa-solid participant-pill-icon shrink-0"
                                                                    :class="a.grading_status === 'grading' ? 'fa-spinner fa-spin' : (a.grading_status === 'done' ? 'fa-check' : 'fa-robot')"></i>
                                                                <span class="truncate" x-text="a.grading_label || '—'"></span>
                                                            </span>
                                                        </template>
                                                        <span x-show="a.tab_switch_count > 0"
                                                            class="participant-pill participant-pill-violation shrink-0"
                                                            :title="a.tab_switch_count + ' kali tab switch'">
                                                            <i class="fa-solid fa-triangle-exclamation participant-pill-icon"></i>
                                                            <span class="tabular-nums" x-text="a.tab_switch_count"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center" x-show="detail.room?.auto_grading_enabled">
                                                <span class="text-sm font-black tabular-nums"
                                                    :class="a.score !== null && a.score !== undefined ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-300 dark:text-gray-600'"
                                                    x-text="a.score !== null && a.score !== undefined ? Number(a.score).toFixed(1) : '—'"></span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-end gap-1">
                                                    <a :href="`{{ url('ujian/attempt/result') }}/${a.uuid}`" target="_blank"
                                                        title="Lihat hasil"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                                        <i class="fa-solid fa-eye text-xs"></i>
                                                    </a>
                                                    <template x-if="a.status === 'AUTO_SUBMITTED_VIOLATION' && detail.room?.status === 'PUBLISHED'">
                                                        <button type="button" @click="confirmResetViolation(a)" title="Reset pelanggaran"
                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100">
                                                            <i class="fa-solid fa-rotate-left text-xs"></i>
                                                        </button>
                                                    </template>
                                                    <button type="button" @click="confirmDeleteAttempt(a)" title="Hapus peserta"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-700 hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
                                                        <i class="fa-solid fa-trash text-xs"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </template>
                </div>
            </div>

            <div class="shrink-0 border-t border-gray-200 bg-white/95 backdrop-blur px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sticky bottom-0">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2 sm:gap-4">
                    <div class="flex shrink-0 items-center gap-2">
                        <button type="button" @click="closeDetail()" class="{{ $btnGray }}">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                        <button type="button" x-show="canDelete" @click="openDeleteConfirm()" class="{{ $btnRed }}">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    </div>
                    <div class="flex shrink-0 flex-row flex-nowrap items-center justify-end gap-2 sm:gap-3">
                        <button type="button" x-show="canBatchGrade" x-cloak @click="startBatchGrading(false)" class="{{ $btnPurple }}">
                            <i class="fas fa-robot"></i> Koreksi AI
                        </button>
                        <button type="button" x-show="canRegrade" x-cloak @click="startBatchGrading(true)" class="{{ $btnPurple }}">
                            <i class="fas fa-rotate"></i> Koreksi Ulang
                        </button>
                        <template x-if="detail.room?.status === 'CLOSED'">
                            <a :href="`{{ url('ujian/rooms') }}/${roomUuid}/export-pdf`" class="{{ $btnGreen }}">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                        </template>
                        <template x-if="detail.room?.status === 'DRAFT'">
                            <button type="button" @click="changeStatus('start')" class="{{ $btnEmerald }}">
                                <i class="fas fa-play"></i> Mulai
                            </button>
                        </template>
                        <template x-if="detail.room?.status === 'CLOSED'">
                            <button type="button" @click="openReopen()" class="{{ $btnEmerald }}">
                                <i class="fas fa-rotate-right"></i> Buka Kembali
                            </button>
                        </template>
                        <template x-if="detail.room?.status === 'PUBLISHED'">
                            <button type="button" @click="changeStatus('close')" class="{{ $btnAmber }}">
                                <i class="fas fa-circle-stop"></i> Tutup
                            </button>
                        </template>
                        <button type="button" @click="enterEditMode()" class="{{ $btnBlue }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODE EDIT --}}
        <form x-show="editMode" novalidate @submit.prevent="submitEdit()" class="flex flex-col flex-1 overflow-hidden" x-cloak>
            <div class="flex-1 overflow-y-auto custom-scrollbar p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a]">

                {{-- Section: Paket Soal --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-file-circle-check text-indigo-500 dark:text-indigo-400"></i> Paket Soal
                        </h4>
                    </div>
                    <div class="p-5 space-y-4">
                        <div x-show="!changingPackage" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Fakultas</label>
                                <div class="{{ $inputClass }} cursor-default opacity-90"
                                    x-text="editProposalContext?.fakultas_name || '-'"></div>
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Program Studi</label>
                                <div class="{{ $inputClass }} cursor-default opacity-90"
                                    x-text="editProposalContext?.prodi_name || '-'"></div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Mata Kuliah</label>
                                <div class="{{ $inputClass }} cursor-default opacity-90"
                                    x-text="editProposalContext?.course_name || '-'"></div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Paket Soal (Approved)</label>
                                <div class="{{ $inputClass }} cursor-default opacity-90"
                                    x-text="proposalShortLabel(editProposalContext)"></div>
                            </div>
                            <p class="md:col-span-2 text-[11px] text-gray-500 dark:text-gray-400" x-show="hasAttempts" x-cloak>
                                Sudah ada peserta — paket soal dikunci.
                            </p>
                            <div class="md:col-span-2" x-show="canChangePackage" x-cloak>
                                <button type="button" @click="startChangePackage()"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 transition hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    <i class="fa-solid fa-pen-to-square"></i> Ubah paket soal
                                </button>
                            </div>
                        </div>

                        <div x-show="changingPackage" x-cloak class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                        Fakultas <span class="text-red-500">*</span>
                                    </label>
                                    <select x-model="filterFakultas" @change="onFakultasChange()" x-ref="fakultasSelect" :required="changingPackage"
                                        class="{{ $inputClass }}">
                                        <option value="">— Pilih fakultas —</option>
                                        @foreach($uniqueFakultas as $p)
                                            <option value="{{ $p['fakultas_id'] }}">{{ $p['fakultas_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                        Program Studi <span class="text-red-500">*</span>
                                    </label>
                                    <select x-model="filterProdi" @change="onProdiChange()" x-ref="prodiSelect" :disabled="!filterFakultas" :required="changingPackage && !!filterFakultas"
                                        class="{{ $inputClass }} disabled:cursor-not-allowed disabled:opacity-60">
                                        <option value="">— Pilih program studi —</option>
                                        @foreach($uniqueProdis as $p)
                                            <option value="{{ $p['prodi_id'] }}" data-fakultas="{{ $p['fakultas_id'] }}">{{ $p['prodi_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                        Mata Kuliah <span class="text-red-500">*</span>
                                    </label>
                                    <select x-model="filterCourse" @change="onCourseChange()" x-ref="courseSelect" :disabled="!filterProdi" :required="changingPackage && !!filterProdi"
                                        class="{{ $inputClass }} disabled:cursor-not-allowed disabled:opacity-60">
                                        <option value="">— Pilih mata kuliah —</option>
                                        @foreach($uniqueCourses as $p)
                                            <option value="{{ $p['course_id'] }}" data-prodi="{{ $p['prodi_id'] }}">{{ $p['course_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                    Paket Soal (Approved) <span class="text-red-500">*</span>
                                </label>
                                <select x-model="form.proposal_id" x-ref="proposalSelect" :disabled="!filterCourse" :required="changingPackage && !!filterCourse"
                                    class="{{ $inputClass }} disabled:cursor-not-allowed disabled:opacity-60">
                                    <option value="">— Pilih paket soal —</option>
                                    @foreach($proposals as $p)
                                        <option value="{{ $p['id'] }}" data-course="{{ $p['course_id'] }}">
                                            {{ $p['exam_type'] }}{{ !empty($p['period_name']) ? ' (' . $p['period_name'] . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" @click="cancelChangePackage()"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-600 transition hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                                <i class="fa-solid fa-times"></i> Batal ubah paket soal
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Section: Informasi Ruang --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-indigo-500 dark:text-indigo-400"></i> Informasi Ruang
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                Judul <span class="text-red-500">*</span>
                            </label>
                            <input x-model="form.title" required maxlength="150" placeholder="Contoh: UTS Pemrograman Web 2026"
                                class="{{ $inputClass }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Deskripsi</label>
                            <textarea x-model="form.description" rows="2" maxlength="1000" placeholder="Catatan untuk peserta ujian (opsional)"
                                class="{{ $inputClass }}"></textarea>
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                Durasi Ujian (menit) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" min="1" max="600" x-model.number="form.duration_minutes" required
                                :disabled="detail.room?.status !== 'DRAFT'"
                                class="{{ $inputClass }} disabled:cursor-not-allowed disabled:opacity-60">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                Waktu Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" x-model="form.start_at" required
                                :disabled="detail.room?.status !== 'DRAFT'"
                                class="{{ $inputClass }} {{ $datetimeClass }} disabled:cursor-not-allowed disabled:opacity-60">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                Kebijakan Tab Switch <span class="text-red-500">*</span>
                            </label>
                            <select x-model="form.tab_switch_policy" class="{{ $inputClass }}">
                                <option value="strict">Tanpa Toleransi (auto-submit 1x)</option>
                                <option value="limited">Limited (ada batas)</option>
                                <option value="unlimited">Tanpa Batas</option>
                            </select>
                        </div>
                        <div x-show="form.tab_switch_policy === 'limited'" x-cloak class="md:col-span-2">
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Batas Tab Switch</label>
                            <input type="number" min="0" max="50" x-model.number="form.tab_switch_limit"
                                class="{{ $inputClass }}">
                        </div>
                    </div>
                </div>

                {{-- Section: Pengaturan Tambahan --}}
                <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-indigo-500 dark:text-indigo-400"></i> Pengaturan Tambahan
                        </h4>
                    </div>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        <li class="{{ $settingRowClass }}">
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-700 dark:text-gray-300">Acak Urutan Soal</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tiap mahasiswa mendapat urutan soal yang berbeda</div>
                            </div>
                            <label class="md:justify-self-end grid grid-cols-[auto_72px] items-center gap-3 cursor-pointer select-none">
                                <span class="relative inline-flex shrink-0">
                                    <input type="checkbox" x-model="form.shuffle_questions" class="sr-only peer">
                                    <span class="{{ $toggleTrackClass }}"></span>
                                </span>
                                <span class="text-sm font-semibold text-right tabular-nums"
                                    :class="form.shuffle_questions ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'"
                                    x-text="form.shuffle_questions ? 'Aktif' : 'Nonaktif'"></span>
                            </label>
                        </li>
                        <li class="{{ $settingRowClass }}">
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-700 dark:text-gray-300">Tampilkan Sisa Waktu</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Mahasiswa melihat countdown waktu pengerjaan</div>
                            </div>
                            <label class="md:justify-self-end grid grid-cols-[auto_72px] items-center gap-3 cursor-pointer select-none">
                                <span class="relative inline-flex shrink-0">
                                    <input type="checkbox" x-model="form.show_remaining_time" class="sr-only peer">
                                    <span class="{{ $toggleTrackClass }}"></span>
                                </span>
                                <span class="text-sm font-semibold text-right tabular-nums"
                                    :class="form.show_remaining_time ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'"
                                    x-text="form.show_remaining_time ? 'Aktif' : 'Nonaktif'"></span>
                            </label>
                        </li>
                        <li class="{{ $settingRowClass }}">
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-700 dark:text-gray-300">Koreksi Otomatis dengan AI</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">AI mengoreksi jawaban setelah mahasiswa submit</div>
                            </div>
                            <label class="md:justify-self-end grid grid-cols-[auto_72px] items-center gap-3 cursor-pointer select-none">
                                <span class="relative inline-flex shrink-0">
                                    <input type="checkbox" x-model="form.auto_grading_enabled" class="sr-only peer">
                                    <span class="{{ $toggleTrackClass }}"></span>
                                </span>
                                <span class="text-sm font-semibold text-right tabular-nums"
                                    :class="form.auto_grading_enabled ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'"
                                    x-text="form.auto_grading_enabled ? 'Aktif' : 'Nonaktif'"></span>
                            </label>
                        </li>
                    </ul>
                </div>

                <template x-if="formError">
                    <div class="flex items-start gap-3 border-l-4 border-red-500 bg-red-50 p-4 rounded-r-lg dark:bg-red-900/20 dark:border-red-400">
                        <i class="fa-solid fa-circle-exclamation text-red-600 dark:text-red-400 mt-0.5 shrink-0"></i>
                        <p class="text-sm font-semibold text-red-700 dark:text-red-300" x-text="formError"></p>
                    </div>
                </template>
            </div>

            {{-- FOOTER --}}
            <div class="shrink-0 border-t border-gray-200 bg-white/95 backdrop-blur px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sticky bottom-0">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2">
                    <button type="button" @click="cancelEdit()" class="{{ $btnGray }}">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" :disabled="submitting" class="{{ $btnBlue }} disabled:opacity-60 disabled:cursor-not-allowed">
                        <i class="fas fa-save"></i>
                        <span x-text="submitting ? 'Menyimpan…' : 'Simpan'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</template>

{{-- Reopen modal --}}
<template x-teleport="#modal-root">
<div x-data="openRoomReopenModal()" @open-reopen-modal.window="handleOpenReopen($event)" x-show="openReopen"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40 z-[10000001]"
    x-transition x-cloak>
    <div @click.away="openReopen = false" class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl dark:bg-[#0f172a] overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-rotate-right text-emerald-500"></i> Buka Kembali Ruang Ujian
            </h3>
        </div>
        <form @submit.prevent="submitReopen()" class="p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Berakhir Baru *</label>
                <input type="datetime-local" x-model="reopenForm.end_at" required
                    class="datetime-input w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Durasi (menit) — opsional</label>
                <input type="number" min="1" max="600" x-model.number="reopenForm.duration_minutes"
                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
            </div>
            <template x-if="reopenError">
                <div class="border-l-4 border-red-500 bg-red-50 p-3 text-sm text-red-700 rounded-r-lg" x-text="reopenError"></div>
            </template>
            <div class="flex justify-end gap-2 sm:gap-3">
                <button type="button" @click="openReopen = false" class="{{ $btnGray }}">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" :disabled="reopenSubmitting" class="{{ $btnEmerald }} disabled:opacity-60 disabled:cursor-not-allowed">
                    <i class="fas fa-rotate-right"></i>
                    <span x-text="reopenSubmitting ? 'Memproses…' : 'Buka Kembali'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
</template>

{{-- Confirm modal --}}
<template x-teleport="#modal-root">
<div x-data="openRoomConfirmModal()" @open-room-confirm.window="handleOpenConfirm($event)" x-show="confirmModal.open"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/50 z-[10000002]"
    x-transition x-cloak>
    <div @click.away="closeConfirmModal()" class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl dark:bg-[#0f172a] overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100" x-text="confirmModal.title"></h3>
        </div>
        <div class="p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a]">
            <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line" x-text="confirmModal.message"></p>
            <div class="flex justify-end gap-2 sm:gap-3">
                <button type="button" @click="closeConfirmModal()" :disabled="confirmModal.submitting" class="{{ $btnGray }} disabled:opacity-60">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" @click="executeConfirmAction()" :disabled="confirmModal.submitting" class="{{ $btnRed }} disabled:opacity-60 disabled:cursor-not-allowed">
                    <i class="fas fa-check"></i>
                    <span x-text="confirmModal.submitting ? 'Memproses…' : 'Ya, Lanjutkan'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
</template>

{{-- Batch grading modal --}}
<template x-teleport="#modal-root">
<div x-data="openRoomBatchGradingModal()" @open-batch-grading.window="handleOpenBatchGrading($event)" x-show="batchGrading.open"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/50 z-[10000003]"
    x-transition x-cloak>
    <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl dark:bg-[#0f172a] overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-robot text-purple-500"></i> Koreksi Otomatis dengan AI
            </h3>
        </div>
        <div class="p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">
            <div class="space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-300">Progress</span>
                    <span class="font-bold text-gray-900 dark:text-white" x-text="batchGrading.progressText"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700 overflow-hidden">
                    <div class="bg-purple-600 h-3 rounded-full transition-all duration-300"
                        :style="`width: ${batchGrading.progressPercent}%`"></div>
                </div>
            </div>
            <div class="rounded-lg bg-blue-50 border border-blue-200 p-3 dark:bg-blue-900/20 dark:border-blue-900/40">
                <p class="text-sm text-blue-800 dark:text-blue-200" x-text="batchGrading.message"></p>
            </div>
            <template x-if="batchGrading.status === 'processing'">
                <div class="flex justify-end">
                    <button type="button" @click="cancelBatchGrading()" class="{{ $btnRed }}">
                        <i class="fas fa-stop"></i> Batalkan
                    </button>
                </div>
            </template>
            <template x-if="batchGrading.status === 'completed' || batchGrading.status === 'cancelled'">
                <div class="flex justify-end">
                    <button type="button" @click="closeBatchGrading()" class="{{ $btnBlue }}">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                </div>
            </template>
        </div>
    </div>
</div>
</template>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }

    .setting-row:hover { background-color: rgb(249 250 251); }
    .dark .setting-row:hover { background-color: rgba(51, 65, 85, 0.55); }

    .datetime-input { color-scheme: light; }
    input[type="datetime-local"].datetime-input::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 1;
    }
    .dark .datetime-input { color-scheme: dark; }
    .dark input[type="datetime-local"].datetime-input::-webkit-calendar-picker-indicator {
        filter: brightness(0) invert(1);
        opacity: 1;
    }

    .access-code-char {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .access-code-char:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
    }

    .participant-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        height: 1.375rem;
        min-height: 1.375rem;
        padding: 0 8px;
        border-radius: 9999px;
        border-width: 1px;
        border-style: solid;
        font-size: 10px;
        font-weight: 700;
        line-height: 1;
        white-space: nowrap;
        vertical-align: middle;
    }
    .participant-pill-icon {
        font-size: 8px;
        line-height: 1;
    }
    .participant-pill-violation {
        background-color: rgb(254 226 226);
        color: rgb(185 28 28);
        border-color: rgb(254 202 202);
    }
    .dark .participant-pill-violation {
        background-color: rgba(127, 29, 29, 0.35);
        color: rgb(252 165 165);
        border-color: rgba(185, 28, 28, 0.45);
    }
    .participant-table thead th {
        background-color: rgb(249 250 251);
    }
    .dark .participant-table thead th {
        background-color: rgba(55, 65, 81, 0.5);
        color: rgb(209 213 219);
    }
    .participant-table tbody tr.participant-row:nth-child(even) {
        background-color: rgba(248, 250, 252, 0.55);
    }
    .dark .participant-table tbody tr.participant-row:nth-child(even) {
        background-color: rgba(15, 23, 42, 0.3);
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('openRoomDetailModal', () => ({
            openDetail: false,
            editMode: false,
            roomUuid: '',
            roomTitle: '',
            deleteUrl: '',
            canDelete: false,
            hasAttempts: false,
            submitting: false,
            formError: '',
            formSnapshot: null,
            form: {},
            detail: { room: null, attempts: [], proposal_context: null, summary: null },
            livePolling: false,
            liveUpdating: false,
            livePollInterval: null,
            lastLiveUpdate: null,
            editProposalContext: null,
            proposalsAll: @json($proposals),
            filterFakultas: '',
            filterProdi: '',
            filterCourse: '',
            changingPackage: false,
            linkCopied: false,
            linkCopiedTimer: null,
            csrf: document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',

            get accessCodeChars() {
                const code = (this.detail.room?.room_code || '').toUpperCase();
                return code ? [...code] : [];
            },

            get joinShareUrl() {
                const code = (this.detail.room?.room_code || '').trim();
                if (!code) return '';
                return `${window.location.origin}{{ route('ujian.attempt.scan', [], false) }}?code=${encodeURIComponent(code)}`;
            },

            get canBatchGrade() {
                const pending = Number(this.detail.summary?.grading_pending ?? 0);
                if (pending > 0) {
                    return true;
                }

                return (this.detail.attempts || []).some((a) =>
                    ['SUBMITTED', 'AUTO_SUBMITTED_TIME', 'AUTO_SUBMITTED_VIOLATION'].includes(a.status) &&
                    (a.score === null || a.score === undefined)
                );
            },

            get canRegrade() {
                const finished = Number(this.detail.summary?.finished ?? 0);
                const pending = Number(this.detail.summary?.grading_pending ?? 0);
                if (finished > 0 && pending === 0) {
                    return true;
                }

                const done = (this.detail.attempts || []).filter((a) =>
                    ['SUBMITTED', 'AUTO_SUBMITTED_TIME', 'AUTO_SUBMITTED_VIOLATION'].includes(a.status)
                );

                return done.length > 0 && done.every((a) => a.score !== null && a.score !== undefined);
            },

            statusLabel(status) {
                return { DRAFT: 'Menunggu', PUBLISHED: 'Berjalan', CLOSED: 'Selesai' }[status] || status || '-';
            },

            participantInitial(name) {
                const n = (name || '?').trim();
                return n ? n.charAt(0).toUpperCase() : '?';
            },

            participantAvatarClass(name) {
                const palettes = [
                    'bg-indigo-50 text-indigo-700 border-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300 dark:border-indigo-800',
                    'bg-violet-50 text-violet-700 border-violet-100 dark:bg-violet-900/30 dark:text-violet-300 dark:border-violet-800',
                    'bg-sky-50 text-sky-700 border-sky-100 dark:bg-sky-900/30 dark:text-sky-300 dark:border-sky-800',
                    'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800',
                    'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
                ];
                const n = (name || '').trim();
                if (!n) return palettes[0];
                let hash = 0;
                for (let i = 0; i < n.length; i++) {
                    hash = n.charCodeAt(i) + ((hash << 5) - hash);
                }
                return palettes[Math.abs(hash) % palettes.length];
            },

            participantProgressClass(attempt) {
                const pct = this.participantAnswerPercent(attempt);
                if (attempt?.status === 'ONGOING') return 'bg-amber-500';
                if (pct >= 100) return 'bg-emerald-500';
                return 'bg-indigo-500';
            },

            participantAnswerPercent(attempt) {
                const total = Number(attempt?.total_questions || 0);
                const answered = Number(attempt?.answered || 0);
                if (total <= 0) return 0;
                return Math.min(100, Math.round((answered / total) * 100));
            },

            participantStatusClass(attempt) {
                if (attempt?.status === 'ONGOING') {
                    return 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800';
                }
                if (attempt?.status === 'SUBMITTED') {
                    return 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800';
                }
                return 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800';
            },

            participantGradingClass(attempt) {
                const map = {
                    grading: 'bg-purple-100 text-purple-700 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800',
                    pending: 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
                    pending_manual: 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
                    done: 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800',
                    working: 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                    none: 'bg-gray-100 text-gray-600 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700',
                };
                return map[attempt?.grading_status] || map.none;
            },

            proposalShortLabel(ctx) {
                if (!ctx) return '-';
                if (ctx.short_label) return ctx.short_label;
                return ctx.exam_type + (ctx.period_name ? ` (${ctx.period_name})` : '');
            },

            resolveProposalContext(proposalId = null, fallback = null) {
                if (fallback?.id) {
                    return fallback;
                }
                const id = proposalId ?? this.form.proposal_id ?? this.detail.room?.proposal_id;
                if (!id) {
                    return null;
                }
                if (this.detail.proposal_context && String(this.detail.proposal_context.id) === String(id)) {
                    return this.detail.proposal_context;
                }
                return this.proposalsAll.find((p) => String(p.id) === String(id)) || null;
            },

            syncEditProposalContext(fallback = null) {
                const ctx = this.resolveProposalContext(this.form.proposal_id, fallback);
                this.editProposalContext = ctx;
                if (ctx?.id) {
                    this.ensureProposalInList(ctx);
                    this.form.proposal_id = String(ctx.id);
                }
            },

            get canChangePackage() {
                return !this.hasAttempts && this.detail.room?.status === 'DRAFT';
            },

            get facultiesList() {
                const map = new Map();
                this.proposalsAll.forEach((p) => {
                    if (p.fakultas_id) {
                        map.set(String(p.fakultas_id), { id: String(p.fakultas_id), name: p.fakultas_name });
                    }
                });
                return [...map.values()].sort((a, b) => a.name.localeCompare(b.name));
            },

            get prodisList() {
                if (!this.filterFakultas) return [];
                const map = new Map();
                this.proposalsAll
                    .filter((p) => String(p.fakultas_id) === String(this.filterFakultas))
                    .forEach((p) => {
                        if (p.prodi_id) {
                            map.set(String(p.prodi_id), { id: String(p.prodi_id), name: p.prodi_name });
                        }
                    });
                return [...map.values()].sort((a, b) => a.name.localeCompare(b.name));
            },

            get coursesList() {
                if (!this.filterProdi) return [];
                const map = new Map();
                this.proposalsAll
                    .filter((p) => String(p.prodi_id) === String(this.filterProdi))
                    .forEach((p) => {
                        if (p.course_id) {
                            map.set(String(p.course_id), { id: String(p.course_id), name: p.course_name });
                        }
                    });
                return [...map.values()].sort((a, b) => a.name.localeCompare(b.name));
            },

            get filteredProposals() {
                if (!this.filterCourse) return [];
                return this.proposalsAll
                    .filter((p) => String(p.course_id) === String(this.filterCourse))
                    .map((p) => ({
                        ...p,
                        short_label: p.exam_type + (p.period_name ? ` (${p.period_name})` : ''),
                    }));
            },

            onFakultasChange() {
                if (this.hasAttempts) return;
                this.filterProdi = '';
                this.filterCourse = '';
                this.form.proposal_id = '';
                this.syncPackageSelectOptions();
            },

            onProdiChange() {
                if (this.hasAttempts) return;
                this.filterCourse = '';
                this.form.proposal_id = '';
                this.syncPackageSelectOptions();
            },

            onCourseChange() {
                if (this.hasAttempts) return;
                this.form.proposal_id = '';
                this.syncPackageSelectOptions();
                const select = this.$refs.proposalSelect;
                if (!select) return;
                const visible = Array.from(select.options).filter((opt, i) => i > 0 && !opt.hidden);
                if (visible.length === 1) {
                    this.form.proposal_id = String(visible[0].value);
                }
            },

            syncPackageSelectOptions() {
                const prodi = this.$refs.prodiSelect;
                const course = this.$refs.courseSelect;
                const proposal = this.$refs.proposalSelect;
                if (prodi) {
                    Array.from(prodi.options).forEach((opt, i) => {
                        if (i === 0) return;
                        opt.hidden = !this.filterFakultas || String(opt.dataset.fakultas) !== String(this.filterFakultas);
                    });
                }
                if (course) {
                    Array.from(course.options).forEach((opt, i) => {
                        if (i === 0) return;
                        opt.hidden = !this.filterProdi || String(opt.dataset.prodi) !== String(this.filterProdi);
                    });
                }
                if (proposal) {
                    Array.from(proposal.options).forEach((opt, i) => {
                        if (i === 0) return;
                        opt.hidden = !this.filterCourse || String(opt.dataset.course) !== String(this.filterCourse);
                    });
                }
            },

            ensureProposalInList(proposal) {
                if (!proposal?.id) return;
                const exists = this.proposalsAll.some((item) => String(item.id) === String(proposal.id));
                if (!exists) {
                    this.proposalsAll = [...this.proposalsAll, proposal];
                }
            },

            applyFiltersFromProposal(proposalId, fallback = null) {
                const p = this.proposalsAll.find((item) => String(item.id) === String(proposalId)) || fallback;
                if (!p) {
                    this.filterFakultas = '';
                    this.filterProdi = '';
                    this.filterCourse = '';
                    this.form.proposal_id = proposalId ? String(proposalId) : '';
                    return;
                }

                this.ensureProposalInList(p);
                this.filterFakultas = p.fakultas_id ? String(p.fakultas_id) : '';
                this.filterProdi = p.prodi_id ? String(p.prodi_id) : '';
                this.filterCourse = p.course_id ? String(p.course_id) : '';
                this.form.proposal_id = String(p.id);
            },

            async handleOpenDetail(event) {
                this.roomUuid = event.detail?.uuid || '';
                this.roomTitle = event.detail?.title || '';
                this.deleteUrl = event.detail?.deleteUrl || '';
                this.canDelete = !!event.detail?.canDelete;
                this.editMode = false;
                this.changingPackage = false;
                this.formError = '';
                this.editProposalContext = null;
                this.detail = { room: null, attempts: [], proposal_context: null, summary: null };
                this.openDetail = true;
                await this.reloadDetail();
                this.startLivePolling();
            },

            async reloadDetail() {
                if (!this.roomUuid) return;
                try {
                    const res = await fetch(`{{ url('ujian/rooms') }}/${this.roomUuid}`, {
                        headers: { 'Accept': 'application/json' },
                    });
                    if (!res.ok) throw new Error('Gagal memuat detail');
                    this.detail = await res.json();
                    this.applyMonitorData(this.detail);
                    if (this.detail.proposal_context) {
                        this.ensureProposalInList(this.detail.proposal_context);
                    }
                    if (this.editMode) {
                        this.syncEditProposalContext(this.detail.proposal_context || null);
                    }
                    this.$nextTick(() => this.renderQR());
                } catch (e) {
                    this.stopLivePolling();
                    window.dispatchEvent(new CustomEvent('rooms-data-changed', {
                        bubbles: true,
                        detail: { type: 'error', message: e.message },
                    }));
                    this.openDetail = false;
                }
            },

            applyMonitorData(data) {
                if (!data) return;
                if (Array.isArray(data.attempts)) {
                    this.detail.attempts = data.attempts;
                }
                if (data.summary) {
                    this.detail.summary = data.summary;
                }
                if (data.total_questions) {
                    this.detail.total_questions = data.total_questions;
                }
                if (data.room && this.detail.room) {
                    this.detail.room = { ...this.detail.room, ...data.room };
                }
                this.hasAttempts = (this.detail.attempts || []).length > 0;
                if (data.server_time) {
                    this.lastLiveUpdate = data.server_time;
                }
            },

            startLivePolling() {
                this.stopLivePolling();
                this.livePolling = true;
                this.pollLiveMonitor();
                this.livePollInterval = setInterval(() => this.pollLiveMonitor(), 3000);
            },

            stopLivePolling() {
                this.livePolling = false;
                if (this.livePollInterval) {
                    clearInterval(this.livePollInterval);
                    this.livePollInterval = null;
                }
            },

            async pollLiveMonitor() {
                if (!this.roomUuid || !this.openDetail || this.editMode) return;
                this.liveUpdating = true;
                try {
                    const res = await fetch(`{{ url('ujian/rooms') }}/${this.roomUuid}/live-monitor`, {
                        headers: { 'Accept': 'application/json' },
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    this.applyMonitorData(data);
                } catch (e) {
                    // Polling gagal — coba lagi interval berikutnya
                } finally {
                    this.liveUpdating = false;
                }
            },

            renderQR() {
                if (!this.$refs.qrCanvas || !this.joinShareUrl) return;
                this.$refs.qrCanvas.innerHTML = '';
                new QRCode(this.$refs.qrCanvas, {
                    text: this.joinShareUrl,
                    width: 140,
                    height: 140,
                    colorDark: '#312e81',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.M,
                });
            },

            async copyJoinLink() {
                const link = this.joinShareUrl;
                if (!link) return;

                const showCopied = () => {
                    this.linkCopied = true;
                    if (this.linkCopiedTimer) clearTimeout(this.linkCopiedTimer);
                    this.linkCopiedTimer = setTimeout(() => { this.linkCopied = false; }, 2000);
                };

                try {
                    if (navigator.clipboard?.writeText) {
                        await navigator.clipboard.writeText(link);
                        showCopied();
                        return;
                    }
                } catch (e) { /* fallback below */ }

                const el = document.createElement('textarea');
                el.value = link;
                el.setAttribute('readonly', '');
                el.style.position = 'absolute';
                el.style.left = '-9999px';
                document.body.appendChild(el);
                el.select();
                try {
                    document.execCommand('copy');
                    showCopied();
                } catch (e) {
                    window.dispatchEvent(new CustomEvent('rooms-data-changed', {
                        bubbles: true,
                        detail: { type: 'error', message: 'Gagal menyalin link. Salin manual: ' + link },
                    }));
                } finally {
                    document.body.removeChild(el);
                }
            },

            buildFormFromRoom(room) {
                return {
                    proposal_id: room.proposal_id ? String(room.proposal_id) : '',
                    title: room.title,
                    description: room.description || '',
                    start_at: room.start_at?.replace(' ', 'T').slice(0, 16) || '',
                    duration_minutes: room.duration_minutes,
                    tab_switch_policy: room.tab_switch_policy,
                    tab_switch_limit: room.tab_switch_limit,
                    shuffle_questions: !!room.shuffle_questions,
                    show_remaining_time: !!room.show_remaining_time,
                    auto_grading_enabled: !!room.auto_grading_enabled,
                };
            },

            startChangePackage() {
                this.changingPackage = true;
                this.applyFiltersFromProposal(
                    this.form.proposal_id,
                    this.editProposalContext || this.detail.proposal_context || null
                );
                this.$nextTick(() => {
                    this.syncPackageSelectOptions();
                    if (this.$refs.proposalSelect && this.form.proposal_id) {
                        this.$refs.proposalSelect.value = String(this.form.proposal_id);
                    }
                });
            },

            cancelChangePackage() {
                this.changingPackage = false;
                this.syncEditProposalContext(this.detail.proposal_context || null);
            },

            closeDetail() {
                this.stopLivePolling();
                this.openDetail = false;
            },

            enterEditMode() {
                if (!this.detail.room) return;
                this.stopLivePolling();
                this.changingPackage = false;
                this.form = this.buildFormFromRoom(this.detail.room);
                this.syncEditProposalContext(this.detail.proposal_context || null);
                this.formSnapshot = JSON.parse(JSON.stringify(this.form));
                this.editMode = true;
                this.formError = '';
            },

            cancelEdit() {
                this.form = this.formSnapshot ? JSON.parse(JSON.stringify(this.formSnapshot)) : {};
                this.changingPackage = false;
                this.editProposalContext = null;
                this.editMode = false;
                this.formError = '';
                this.startLivePolling();
            },

            async submitEdit() {
                this.submitting = true;
                this.formError = '';
                const payload = {
                    ...this.form,
                    proposal_id: this.form.proposal_id || this.editProposalContext?.id || this.detail.room?.proposal_id,
                };
                if (!payload.proposal_id) {
                    this.formError = this.changingPackage
                        ? 'Lengkapi fakultas, prodi, mata kuliah, dan paket soal terlebih dahulu.'
                        : 'Paket soal tidak ditemukan. Muat ulang halaman lalu coba lagi.';
                    this.submitting = false;
                    return;
                }
                try {
                    const res = await fetch(`{{ url('ujian/rooms') }}/${this.roomUuid}/update`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrf,
                        },
                        body: JSON.stringify(payload),
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        throw new Error(data?.message || (data?.errors ? Object.values(data.errors).flat().join(', ') : 'Gagal menyimpan.'));
                    }
                    this.editMode = false;
                    await this.reloadDetail();
                    this.startLivePolling();
                    window.dispatchEvent(new CustomEvent('rooms-data-changed', {
                        bubbles: true,
                        detail: { message: data.message || 'Ruang ujian diperbarui.' },
                    }));
                } catch (e) {
                    this.formError = e.message;
                } finally {
                    this.submitting = false;
                }
            },

            openDeleteConfirm() {
                this.closeDetail();
                window.dispatchEvent(new CustomEvent('open-delete-modal', {
                    bubbles: true,
                    detail: { deleteUrl: this.deleteUrl, title: this.roomTitle },
                }));
            },

            openReopen() {
                window.dispatchEvent(new CustomEvent('open-reopen-modal', {
                    bubbles: true,
                    detail: {
                        uuid: this.roomUuid,
                        duration_minutes: this.detail.room?.duration_minutes,
                    },
                }));
            },

            changeStatus(action) {
                const messages = {
                    start: ['Mulai Ujian', 'Yakin ingin memulai ujian sekarang?\n\nMahasiswa dapat langsung masuk menggunakan kode ruang.'],
                    close: ['Tutup Ruang Ujian', 'Yakin ingin menutup ruang ujian ini?\n\nMahasiswa tidak dapat lagi masuk atau melanjutkan ujian.'],
                };
                const [title, message] = messages[action] || ['Konfirmasi', 'Lanjutkan?'];
                window.dispatchEvent(new CustomEvent('open-room-confirm', {
                    bubbles: true,
                    detail: { title, message, action, uuid: this.roomUuid },
                }));
            },

            confirmDeleteAttempt(attempt) {
                window.dispatchEvent(new CustomEvent('open-room-confirm', {
                    bubbles: true,
                    detail: {
                        title: 'Hapus Peserta Ujian',
                        message: `Yakin ingin menghapus ${attempt.user_name || 'peserta ini'}?\n\nSemua jawaban dan log aktivitas akan ikut terhapus.`,
                        action: 'delete-attempt',
                        uuid: this.roomUuid,
                        attempt,
                    },
                }));
            },

            confirmResetViolation(attempt) {
                window.dispatchEvent(new CustomEvent('open-room-confirm', {
                    bubbles: true,
                    detail: {
                        title: 'Reset Pelanggaran',
                        message: `Yakin ingin me-reset pelanggaran untuk ${attempt.user_name || 'peserta ini'}?`,
                        action: 'reset-violation',
                        uuid: this.roomUuid,
                        attempt,
                    },
                }));
            },

            startBatchGrading(forceRegrade = false) {
                window.dispatchEvent(new CustomEvent('open-batch-grading', {
                    bubbles: true,
                    detail: { uuid: this.roomUuid, forceRegrade },
                }));
            },
        }));

        Alpine.data('openRoomReopenModal', () => ({
            openReopen: false,
            roomUuid: '',
            reopenForm: { end_at: '', duration_minutes: null },
            reopenError: '',
            reopenSubmitting: false,
            csrf: document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',

            handleOpenReopen(event) {
                this.roomUuid = event.detail?.uuid || '';
                const d = new Date();
                d.setMinutes(d.getMinutes() + 60 - d.getTimezoneOffset());
                this.reopenForm = {
                    end_at: d.toISOString().slice(0, 16),
                    duration_minutes: event.detail?.duration_minutes || null,
                };
                this.reopenError = '';
                this.openReopen = true;
            },

            async submitReopen() {
                this.reopenSubmitting = true;
                this.reopenError = '';
                try {
                    const payload = { end_at: this.reopenForm.end_at };
                    if (this.reopenForm.duration_minutes) payload.duration_minutes = this.reopenForm.duration_minutes;
                    const res = await fetch(`{{ url('ujian/rooms') }}/${this.roomUuid}/reopen`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrf,
                        },
                        body: JSON.stringify(payload),
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data?.message || 'Gagal membuka ulang.');
                    this.openReopen = false;
                    window.dispatchEvent(new CustomEvent('rooms-data-changed', {
                        bubbles: true,
                        detail: { message: data.message || 'Ruang ujian dibuka kembali.' },
                    }));
                } catch (e) {
                    this.reopenError = e.message;
                } finally {
                    this.reopenSubmitting = false;
                }
            },
        }));

        Alpine.data('openRoomConfirmModal', () => ({
            confirmModal: { open: false, title: '', message: '', action: '', uuid: '', attempt: null, submitting: false },
            csrf: document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',

            handleOpenConfirm(event) {
                this.confirmModal = {
                    open: true,
                    title: event.detail?.title || '',
                    message: event.detail?.message || '',
                    action: event.detail?.action || '',
                    uuid: event.detail?.uuid || '',
                    attempt: event.detail?.attempt || null,
                    submitting: false,
                };
            },

            closeConfirmModal() {
                if (!this.confirmModal.submitting) this.confirmModal.open = false;
            },

            async executeConfirmAction() {
                const { action, uuid, attempt } = this.confirmModal;
                this.confirmModal.submitting = true;
                try {
                    let url = '';
                    let method = 'POST';
                    if (action === 'start' || action === 'close') {
                        url = `{{ url('ujian/rooms') }}/${uuid}/${action}`;
                    } else if (action === 'delete-attempt') {
                        url = `{{ url('ujian/rooms') }}/${uuid}/attempts/${attempt.uuid}/delete`;
                        method = 'POST';
                    } else if (action === 'reset-violation') {
                        url = `{{ url('ujian/rooms') }}/${uuid}/attempts/${attempt.uuid}/reset-violation`;
                    } else {
                        throw new Error('Aksi tidak dikenal');
                    }

                    const res = await fetch(url, {
                        method,
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.csrf, 'Content-Type': 'application/json' },
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data?.message || 'Gagal memproses.');

                    this.confirmModal.open = false;
                    window.dispatchEvent(new CustomEvent('rooms-data-changed', {
                        bubbles: true,
                        detail: { message: data.message || 'Berhasil.' },
                    }));
                } catch (e) {
                    window.dispatchEvent(new CustomEvent('rooms-data-changed', {
                        bubbles: true,
                        detail: { type: 'error', message: e.message },
                    }));
                    this.confirmModal.open = false;
                } finally {
                    this.confirmModal.submitting = false;
                }
            },
        }));

        Alpine.data('openRoomBatchGradingModal', () => ({
            batchGrading: {
                open: false,
                status: 'idle',
                message: '',
                progressPercent: 0,
                progressText: '0/0',
                failed: [],
                pollingInterval: null,
                roomUuid: '',
            },
            csrf: document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',

            async handleOpenBatchGrading(event) {
                const forceRegrade = !!event.detail?.forceRegrade;
                const message = forceRegrade
                    ? 'Koreksi ulang semua peserta dengan AI? Nilai yang sudah ada akan ditimpa.'
                    : 'Koreksi semua peserta yang belum dinilai dengan AI?';
                const ok = await Alpine.store('confirm').ask({
                    title: 'Koreksi dengan AI',
                    message,
                    confirmLabel: forceRegrade ? 'Ya, Koreksi Ulang' : 'Ya, Koreksi',
                    variant: 'purple',
                });
                if (!ok) return;

                this.batchGrading = {
                    open: true,
                    status: 'processing',
                    message: 'Memulai koreksi...',
                    progressPercent: 0,
                    progressText: '0/0',
                    failed: [],
                    pollingInterval: null,
                    roomUuid: event.detail?.uuid || '',
                };

                this.startGrading(forceRegrade);
            },

            async startGrading(forceRegrade) {
                try {
                    const startRes = fetch(`{{ url('ujian/rooms') }}/${this.batchGrading.roomUuid}/grade-all-attempts`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrf,
                        },
                        body: JSON.stringify({ force_regrade: forceRegrade }),
                    });
                    this.startPollingProgress();
                    const res = await startRes;
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data?.message || 'Gagal memulai koreksi.');
                } catch (e) {
                    this.batchGrading.status = 'completed';
                    this.batchGrading.message = 'Error: ' + e.message;
                    this.stopPollingProgress();
                }
            },

            startPollingProgress() {
                this.batchGrading.pollingInterval = setInterval(() => this.pollProgress(), 2000);
            },

            stopPollingProgress() {
                if (this.batchGrading.pollingInterval) {
                    clearInterval(this.batchGrading.pollingInterval);
                    this.batchGrading.pollingInterval = null;
                }
            },

            async pollProgress() {
                try {
                    const res = await fetch(`{{ url('ujian/rooms') }}/${this.batchGrading.roomUuid}/grading-progress`, {
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.csrf },
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok || data.status === 'idle') return;

                    this.batchGrading.status = data.status;
                    this.batchGrading.message = data.message || '';
                    this.batchGrading.failed = data.failed || [];
                    if (data.total_attempts > 0) {
                        this.batchGrading.progressPercent = Math.round((data.current_attempt / data.total_attempts) * 100);
                        this.batchGrading.progressText = `${data.current_attempt}/${data.total_attempts}`;
                    }
                    if (data.status === 'completed' || data.status === 'cancelled') {
                        this.stopPollingProgress();
                        window.dispatchEvent(new CustomEvent('rooms-data-changed', { bubbles: true }));
                    }
                } catch (e) {
                    console.error(e);
                }
            },

            async cancelBatchGrading() {
                if (!confirm('Batalkan proses koreksi?')) return;
                try {
                    await fetch(`{{ url('ujian/rooms') }}/${this.batchGrading.roomUuid}/cancel-grading`, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.csrf },
                    });
                    this.batchGrading.message = 'Membatalkan proses...';
                } catch (e) {
                    alert('Gagal membatalkan: ' + e.message);
                }
            },

            closeBatchGrading() {
                this.stopPollingProgress();
                this.batchGrading.open = false;
                window.dispatchEvent(new CustomEvent('rooms-data-changed', { bubbles: true }));
            },
        }));
    });
</script>