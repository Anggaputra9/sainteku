@extends('layouts.app')

@section('content')
    {{-- Inisialisasi Alpine.js state untuk modal dan data Bank Soal --}}
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="{ 
            openBankSoal: false, 
            bankSoalList: [], 
            searchQuery: '', 
            isLoading: false,
            fetchBankSoal() {
                this.isLoading = true;
                fetch('{{ route('monevakademik.tashih.api.banksoal', $course->id) }}')
                    .then(res => res.json())
                    .then(data => {
                        this.bankSoalList = data;
                        this.isLoading = false;
                    });
            },
            get filteredBankSoal() {
                if (this.searchQuery === '') return this.bankSoalList;
                return this.bankSoalList.filter(q => q.question_text.toLowerCase().includes(this.searchQuery.toLowerCase()));
            },
            useQuestion(q) {
                // Panggil fungsi JS di bawah untuk nambahin card soal
                addQuestionCard({ text: q.question_text, cpmk: q.cpmk_id, weight: '' });
                this.openBankSoal = false;
                this.searchQuery = '';
            }
         }">

        <div class="space-y-6">
            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Workspace Pengajuan</h2>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">{{ $course->id }} -
                        {{ $course->course_name }}</p>
                </div>
                <div>
                    <span id="weight-badge"
                        class="inline-flex rounded-full bg-gray-100 px-4 py-2 text-sm font-bold text-gray-800 border border-gray-200">
                        Total Bobot: 0 / 100
                    </span>
                </div>
            </div>

            @if(session('error'))
                <div class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm rounded-r-lg">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            <form id="form-pengajuan" action="{{ route('monevakademik.tashih.store') }}" method="POST">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">

                {{-- Header Form --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Jenis
                                Ujian</label>
                            <select name="exam_type"
                                class="w-full rounded-lg border-[1.5px] border-gray-300 bg-transparent px-5 py-3 font-medium outline-none transition focus:border-blue-500 active:border-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                required>
                                <option value="UTS">Ujian Tengah Semester (UTS)</option>
                                <option value="UAS">Ujian Akhir Semester (UAS)</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Periode
                                Akademik</label>
                            <select name="period_id"
                                class="w-full rounded-lg border-[1.5px] border-gray-300 bg-transparent px-5 py-3 font-medium outline-none transition focus:border-blue-500 active:border-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                required>
                                <option value="1">2024/2025 Gasal</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Container Soal Dinamis --}}
                <div id="questions-container" class="space-y-4"></div>

                {{-- Action Buttons --}}
                <div
                    class="mt-8 flex flex-col sm:flex-row justify-between gap-4 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <div class="flex gap-3">
                        <button type="button" onclick="addQuestionCard()"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-200 transition border border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                            <i class="fas fa-plus text-blue-600"></i> Buat Soal Baru
                        </button>
                        {{-- TOMBOL BARU: Buka Modal Bank Soal --}}
                        <button type="button" @click="openBankSoal = true; fetchBankSoal()"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-50 px-6 py-2.5 text-sm font-bold text-indigo-700 hover:bg-indigo-100 transition border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800/50 dark:hover:bg-indigo-900/50">
                            <i class="fas fa-search text-indigo-600 dark:text-indigo-400"></i> Cari dari Bank Soal
                        </button>
                    </div>
                    <button type="submit" id="btn-submit" disabled
                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>

        {{-- MODAL BANK SOAL (ALPINE) --}}
        <div x-show="openBankSoal"
            class="fixed inset-0 z-[999999] flex items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">

            <div @click.away="openBankSoal = false"
                class="relative w-full max-w-4xl transform rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all flex flex-col max-h-[85vh]">

                <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-archive text-indigo-500"></i> Bank Soal: {{ $course->course_name }}
                    </h3>
                    <button @click="openBankSoal = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                {{-- Kolom Pencarian --}}
                <div class="mb-4">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" x-model="searchQuery" placeholder="Cari kata kunci soal..."
                            class="block w-full rounded-lg border-[1.5px] border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                    </div>
                </div>

                {{-- Area List Soal (Scrollable) --}}
                <div class="flex-1 overflow-y-auto pr-2 space-y-3 custom-scrollbar">

                    {{-- Loading State --}}
                    <div x-show="isLoading" class="py-8 text-center text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2 text-indigo-500"></i>
                        <p>Memuat data bank soal...</p>
                    </div>

                    {{-- Empty State --}}
                    <div x-show="!isLoading && filteredBankSoal.length === 0"
                        class="py-8 text-center text-gray-500 dark:text-gray-400" x-cloak>
                        <i class="fas fa-box-open text-4xl mb-3 opacity-50"></i>
                        <p>Tidak ada soal yang ditemukan.</p>
                    </div>

                    {{-- Data List --}}
                    <template x-for="q in filteredBankSoal" :key="q.id">
                        <div
                            class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:border-indigo-300 hover:shadow-md transition dark:border-gray-700 dark:bg-gray-800/50">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <span
                                        class="inline-flex rounded bg-indigo-50 px-2 py-0.5 text-xs font-bold text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 mb-2"
                                        x-text="'CPMK: ' + q.cpmk_id"></span>
                                    <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed"
                                        x-text="q.question_text"></p>
                                </div>
                                <button type="button" @click="useQuestion(q)"
                                    class="shrink-0 rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 transition shadow-sm">
                                    Pilih <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT UNTUK CARD DINAMIS (Sama seperti sebelumnya) --}}
    <script>
        const courseId = "{{ $course->id }}";
        const storageKey = `draft_soal_${courseId}`;
        let questionCount = 0;

        const cpmkOptions = `@foreach($cpmkList as $cpmk) <option value="{{ $cpmk->id }}">{{ $cpmk->id }} - {{ $cpmk->name }}</option> @endforeach`;

        document.addEventListener('DOMContentLoaded', function () {
            loadDraft();
            if (questionCount === 0) addQuestionCard();
        });

        // Fungsi addQuestionCard kita modifikasi sedikit agar mendukung select CPMK otomatis
        function addQuestionCard(data = { text: '', weight: '', cpmk: '' }) {
            questionCount++;
            const html = `
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 overflow-hidden question-card" id="q-card-${questionCount}">
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-3 flex justify-between items-center dark:bg-gray-700/50 dark:border-gray-700">
                        <h5 class="font-bold text-gray-700 dark:text-gray-200">Soal #${questionCount}</h5>
                        <button type="button" onclick="removeCard(${questionCount})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 rounded p-1.5 transition dark:bg-red-900/30 dark:hover:bg-red-900/50"><i class="fas fa-trash"></i></button>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                            <div class="lg:col-span-3">
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Pertanyaan</label>
                                <textarea name="questions[${questionCount}][question_text]" rows="4" onkeyup="saveDraft()" class="q-text w-full rounded-lg border-[1.5px] border-gray-300 bg-transparent px-5 py-3 font-medium outline-none transition focus:border-blue-500 active:border-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white placeholder-gray-400" placeholder="Ketik soal di sini...">${data.text}</textarea>
                            </div>
                            <div class="lg:col-span-1 space-y-4">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">CPMK</label>
                                    <select name="questions[${questionCount}][cpmk_id]" onchange="saveDraft()" class="q-cpmk w-full rounded-lg border-[1.5px] border-gray-300 bg-transparent px-5 py-3 font-medium outline-none transition focus:border-blue-500 active:border-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white" required>
                                        <option value="">-- Pilih --</option>
                                        ${cpmkOptions}
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Bobot Nilai (%)</label>
                                    <input type="number" name="questions[${questionCount}][weight]" value="${data.weight}" onkeyup="calculateWeight(); saveDraft()" onchange="calculateWeight(); saveDraft()" class="q-weight w-full rounded-lg border-[1.5px] border-gray-300 bg-transparent px-5 py-3 font-medium outline-none transition focus:border-blue-500 active:border-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white" required placeholder="Contoh: 20">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('questions-container').insertAdjacentHTML('beforeend', html);

            // Pilih CPMK secara otomatis kalau dari Bank Soal / Draft
            if (data.cpmk) {
                setTimeout(() => {
                    document.querySelector(`#q-card-${questionCount} .q-cpmk`).value = data.cpmk;
                }, 50); // delay dikit biar DOM siap
            }

            calculateWeight();
            saveDraft(); // Langsung save ke draft pas ditarik dari bank soal
        }

        function removeCard(id) { document.getElementById(`q-card-${id}`).remove(); calculateWeight(); saveDraft(); }

        function calculateWeight() {
            let total = 0;
            document.querySelectorAll('.q-weight').forEach(input => { total += Number(input.value) || 0; });
            const badge = document.getElementById('weight-badge');
            const btnSubmit = document.getElementById('btn-submit');

            badge.innerText = `Total Bobot: ${total} / 100`;
            if (total === 100) {
                badge.className = 'inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-bold text-green-800 border border-green-200 dark:bg-green-900/30 dark:text-green-400';
                btnSubmit.disabled = false;
            } else {
                badge.className = 'inline-flex rounded-full bg-red-100 px-4 py-2 text-sm font-bold text-red-800 border border-red-200 dark:bg-red-900/30 dark:text-red-400';
                btnSubmit.disabled = true;
            }
        }

        function saveDraft() {
            let draftData = [];
            document.querySelectorAll('.question-card').forEach(card => {
                draftData.push({
                    text: card.querySelector('.q-text').value,
                    cpmk: card.querySelector('.q-cpmk').value,
                    weight: card.querySelector('.q-weight').value
                });
            });
            localStorage.setItem(storageKey, JSON.stringify(draftData));
        }

        function loadDraft() {
            const saved = localStorage.getItem(storageKey);
            if (saved) {
                const parsed = JSON.parse(saved);
                if (parsed.length > 0) { parsed.forEach(item => addQuestionCard(item)); return; }
            }
        }

        document.getElementById('form-pengajuan').addEventListener('submit', function () { localStorage.removeItem(storageKey); });
    </script>

    <style>
        /* Styling khusus scrollbar untuk area modal */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #475569;
        }
    </style>
@endsection