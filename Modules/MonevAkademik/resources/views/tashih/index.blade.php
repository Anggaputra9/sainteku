@extends('layouts.app')

@section('content')
    {{-- KUNCI SPA: Dibungkus fungsi tashihApp (tanpa kurung tutup) --}}
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="tashihApp" x-cloak>

        <div class="space-y-6">
            {{-- Header & Tombol Utama --}}
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Tashih Soal</h2>
                    <nav>
                        <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                            <li>Monev Akademik /</li>
                            <li class="text-blue-600 dark:text-blue-400">Tashih Soal</li>
                        </ol>
                    </nav>
                </div>
                @if(Auth::user()->hasPermission(3, 'C'))
                <button @click="openSelectCourse = true; courseId = ''; courseName = '';"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fas fa-file-circle-plus text-lg"></i> Buat Pengajuan Baru
                </button>
                @endif
            </div>

            @if (session('success'))
                <div
                    class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                    <i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-sm font-bold text-green-700 dark:text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Tabs Navigation --}}
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <button @click="activeTab = 'saya'"
                    :class="activeTab === 'saya' ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700'"
                    class="px-5 py-2 text-sm font-semibold rounded-full transition flex items-center gap-2">
                    <i class="fa-solid fa-folder-open"></i> Riwayat Pengajuan Saya
                </button>
                @if($isReviewer)
                    <button @click="activeTab = 'review'"
                        :class="activeTab === 'review' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/50'"
                        class="px-5 py-2 text-sm font-semibold rounded-full transition flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-check"></i> Antrean Review
                        <span
                            class="ml-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-white text-red-600 rounded-full dark:bg-red-200">{{ $reviewQueue->count() }}</span>
                    </button>
                @endif
            </div>

            {{-- TAB 1: RIWAYAT PENGAJUAN --}}
            <div x-show="activeTab === 'saya'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <button @click="filterStatus = 'all'"
                        :class="filterStatus === 'all' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-gray-500 border-gray-200'"
                        class="px-3 py-1.5 text-xs font-bold rounded-md border transition">Semua</button>
                    <button @click="filterStatus = 'SUBMITTED'"
                        :class="filterStatus === 'SUBMITTED' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-gray-500 border-gray-200'"
                        class="px-3 py-1.5 text-xs font-bold rounded-md border transition"><i
                            class="fa-solid fa-clock text-blue-500 mr-1"></i> Menunggu</button>
                    <button @click="filterStatus = 'APPROVED'"
                        :class="filterStatus === 'APPROVED' ? 'bg-green-100 text-green-700 border-green-300' : 'bg-white text-gray-500 border-gray-200'"
                        class="px-3 py-1.5 text-xs font-bold rounded-md border transition"><i
                            class="fa-solid fa-check text-green-500 mr-1"></i> Disetujui</button>
                    <button @click="filterStatus = 'REVISED'"
                        :class="filterStatus === 'REVISED' ? 'bg-orange-100 text-orange-700 border-orange-300' : 'bg-white text-gray-500 border-gray-200'"
                        class="px-3 py-1.5 text-xs font-bold rounded-md border transition"><i
                            class="fa-solid fa-pen text-orange-500 mr-1"></i> Revisi</button>
                </div>

                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                            <thead
                                class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-4 font-semibold w-1/3">Mata Kuliah</th>
                                    <th class="px-6 py-4 font-semibold text-center">Jenis & Periode</th>
                                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                                    <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($myProposals as $prop)
                                    <tr x-show="filterStatus === 'all' || filterStatus === '{{ $prop->status }}'"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900 dark:text-white text-base">
                                                {{ $prop->course->course_name }}</div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $prop->course_id }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center"><span
                                                class="font-bold text-gray-700 dark:text-gray-300">{{ $prop->exam_type }}</span>
                                            <div class="text-xs text-gray-500 mt-0.5">2024/2025 Gasal</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php $statusClass = $prop->status == 'APPROVED' ? 'bg-green-100 text-green-800 border-green-200' : ($prop->status == 'REVISED' ? 'bg-orange-100 text-orange-800 border-orange-200' : 'bg-blue-100 text-blue-800 border-blue-200'); @endphp
                                            <span
                                                class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border {{ $statusClass }}">{{ $prop->status }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button type="button" @click="viewDetail('{{ $prop->uuid }}', 'saya')"
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 transition shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                                <i class="fa-solid fa-eye text-blue-500"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500"><i
                                                class="fas fa-folder-open text-3xl mb-3 opacity-50"></i><br>Belum ada riwayat
                                            pengajuan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TAB 2: ANTREAN REVIEW --}}
            @if($isReviewer)
                <div x-show="activeTab === 'review'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                                <thead
                                    class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold">Dosen Pengaju</th>
                                        <th class="px-6 py-4 font-semibold">Mata Kuliah</th>
                                        <th class="px-6 py-4 font-semibold text-center">Jenis</th>
                                        <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($reviewQueue as $queue)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                            <td class="px-6 py-4 font-semibold">{{ $queue->creator->name ?? 'Dosen' }}</td>
                                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                                {{ $queue->course->course_name }}</td>
                                            <td class="px-6 py-4 text-center font-semibold">{{ $queue->exam_type }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <button type="button" @click="viewDetail('{{ $queue->uuid }}', 'review')"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-orange-500 px-3 py-1.5 text-xs font-bold text-white hover:bg-orange-600 transition shadow-sm">
                                                    <i class="fa-solid fa-magnifying-glass"></i> Review
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Tidak ada antrean review.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- PANGGIL PARTIALS MODAL DI SINI --}}
        @include('monevakademik::tashih.partials.modal-select-course')
        @include('monevakademik::tashih.partials.modal-create-workspace')
        @include('monevakademik::tashih.partials.modal-bank-soal')
        @include('monevakademik::tashih.partials.modal-detail')

    </div>

    {{-- OTAK UTAMA SPA (SINGLE PAGE APPLICATION) ALPINE.JS --}}
    <script>
        // Daftarkan komponen secara resmi biar nggak bentrok sama loading browser
        document.addEventListener('alpine:init', () => {
            Alpine.data('tashihApp', () => ({
                activeTab: 'saya', filterStatus: 'all',
                openSelectCourse: false, openCreate: false, openBankSoal: false,
                openDetail: false, openApprove: false, openRevise: false, openDelete: false,

                courseId: '', courseName: '',
                isEditMode: false, editUuid: '',

                bankSoalList: [], searchQuery: '', isLoading: false,

                // Variabel aman anti syntax error
                myProposals: @json($myProposals ?? []),
                reviewQueue: @json($reviewQueue ?? []),
                selectedProposal: null,
                userId: '{{ Auth::id() ?? 0 }}',
                isReviewer: {{ $isReviewer ? 'true' : 'false' }},

                fetchBankSoal() {
                    this.isLoading = true;
                    fetch(`{{ url('monev-akademik/tashih/api/bank-soal') }}/${this.courseId}`)
                        .then(res => res.json())
                        .then(data => { this.bankSoalList = data; this.isLoading = false; })
                        .catch(err => { console.error('Gagal fetch soal', err); this.isLoading = false; });
                },

                get filteredBankSoal() {
                    if (this.searchQuery === '') return this.bankSoalList;
                    return this.bankSoalList.filter(q => q.question_text && q.question_text.toLowerCase().includes(this.searchQuery.toLowerCase()));
                },

                useQuestion(q) {
                    if (typeof addQuestionCard === 'function') {
                        addQuestionCard({ text: q.question_text, cpmk: q.cpmk_id, weight: '' });
                    }
                    this.openBankSoal = false; this.searchQuery = '';
                    setTimeout(() => {
                        const mb = document.getElementById('modal-body-scroll');
                        if (mb) mb.scrollTo({ top: mb.scrollHeight, behavior: 'smooth' });
                    }, 100);
                },

                viewDetail(uuid, source) {
                    this.selectedProposal = source === 'saya'
                        ? this.myProposals.find(p => p.uuid === uuid)
                        : this.reviewQueue.find(p => p.uuid === uuid);
                    this.openDetail = true;
                },

                openEditModal() {
                    this.openDetail = false;
                    this.isEditMode = true;
                    this.editUuid = this.selectedProposal.uuid;
                    this.courseId = this.selectedProposal.course_id;
                    this.courseName = this.selectedProposal.course ? this.selectedProposal.course.course_name : '';

                    // Antisipasi format dari JSON
                    const questions = this.selectedProposal.exam_questions || this.selectedProposal.examQuestions || [];

                    let existingQuestions = questions.map(eq => ({
                        text: eq.question ? eq.question.question_text : '',
                        cpmk: eq.question ? eq.question.cpmk_id : '',
                        weight: eq.weight
                    }));

                    this.openCreate = true;
                    setTimeout(() => {
                        if (typeof initCreateModal === 'function') {
                            initCreateModal(this.courseId, existingQuestions);
                        }
                    }, 100);
                }
            }));
        });
    </script>
@endsection