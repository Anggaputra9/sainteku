<div x-show="openCreate" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">

    <div
        class="relative w-full max-w-5xl transform rounded-2xl bg-gray-50 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-700 transition-all flex flex-col max-h-[90vh] overflow-hidden">

        {{-- Header Modal --}}
        <div
            class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 z-10 dark:bg-gray-800 dark:border-gray-700 shadow-sm">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-blue-500"></i>
                    <span x-text="isEditMode ? 'Edit Pengajuan Soal' : 'Workspace Soal'"></span>
                </h3>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1"
                    x-text="courseId + ' - ' + courseName"></p>
            </div>
            <div class="flex items-center gap-4">
                <span id="weight-badge"
                    class="inline-flex items-center rounded-full bg-gray-100 px-4 py-2 text-sm font-bold text-gray-800 border border-gray-200 transition-colors">
                    Total Bobot: <span id="weight-number" class="ml-1">0</span> / 100
                </span>
            </div>
        </div>

        {{-- Body Area --}}
        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar relative" id="modal-body-scroll">
            <form id="form-pengajuan"
                :action="isEditMode ? '{{ url('monev-akademik/tashih/update') }}/' + editUuid : '{{ route('monevakademik.tashih.store') }}'"
                method="POST">
                @csrf
                <template x-if="isEditMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <input type="hidden" name="course_id" :value="courseId">

                <div
                    class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Jenis
                                Ujian</label>
                            <select name="exam_type"
                                class="w-full rounded-lg border-[1.5px] border-gray-300 bg-gray-50 px-5 py-3 font-medium outline-none focus:border-blue-500 focus:bg-white transition dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                required>
                                <option value="UTS">Ujian Tengah Semester (UTS)</option>
                                <option value="UAS">Ujian Akhir Semester (UAS)</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Periode
                                Akademik</label>
                            <select name="period_id"
                                class="w-full rounded-lg border-[1.5px] border-gray-300 bg-gray-50 px-5 py-3 font-medium outline-none focus:border-blue-500 focus:bg-white transition dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                required>
                                <option value="1">2024/2025 Gasal</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="questions-container" class="space-y-4"></div>

                {{-- Area Aksi Tambah Soal (Hanya Bank Soal) --}}
                <div id="action-buttons-container" class="mt-4 flex justify-center" style="display: none;">
                    <button type="button" @click="openBankSoal = true; fetchBankSoal()"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-white border border-indigo-200 border-dashed px-8 py-2.5 text-sm font-bold text-indigo-600 hover:bg-indigo-50 transition w-full sm:w-1/2 shadow-sm dark:bg-gray-800 dark:border-indigo-800/50 dark:text-indigo-400 dark:hover:bg-gray-700">
                        <i class="fas fa-search"></i> Cari dari Bank Soal
                    </button>
                </div>

                {{-- Footer Area (Kembali & Submit) --}}
                <div
                    class="mt-8 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <button @click="openCreate = false; isEditMode = false; openDetail = isEditMode ? true : false;"
                        type="button"
                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-yellow-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-yellow-600 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                        <i class="fas fa-arrow-left"></i> Batal & Kembali
                    </button>
                    <button type="submit" id="btn-submit" disabled
                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-green-600 px-8 py-2.5 text-sm font-bold text-white shadow-md hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400">
                        <i class="fas fa-save"></i> <span
                            x-text="isEditMode ? 'Simpan Perubahan' : 'Kirim Pengajuan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let autoAddTimer = null;
    const cpmkOptions = `@foreach($cpmkList as $cpmk) <option value="{{ $cpmk->id }}">{{ $cpmk->id }} - {{ $cpmk->name }}</option> @endforeach`;

    // MENERIMA PARAMETER (cId) dan (existingData) UNTUK EDIT MODE
    function initCreateModal(cId, existingData = null) {
        activeCourseId = cId;
        storageKey = `draft_soal_${activeCourseId}`;
        clearTimeout(autoAddTimer);

        const container = document.getElementById('questions-container');
        if (container) {
            container.innerHTML = '';

            if (existingData && existingData.length > 0) {
                // Mode Edit: Load data lama
                existingData.forEach(q => addQuestionCard(q));
            } else {
                // Mode Create: Load draft dari LocalStorage
                loadDraft();
                if (document.querySelectorAll('.question-card').length === 0) {
                    addQuestionCard();
                }
            }

            validateFormStates();
            updateQuestionNumbers();
        }
    }

    function addQuestionCard(data = { text: '', weight: '', cpmk: '' }) {
        const uniqueId = Date.now().toString(36) + Math.random().toString(36).substring(2, 8);

        const html = `
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 overflow-hidden question-card" id="q-card-${uniqueId}">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-3 flex justify-between items-center dark:bg-gray-700/50 dark:border-gray-700">
                    <h5 class="font-bold text-gray-700 dark:text-gray-200 question-number-title">Soal #</h5>
                    <button type="button" onclick="removeCard('${uniqueId}')" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 rounded p-1.5 transition dark:bg-red-900/30 delete-card-btn"><i class="fas fa-trash"></i></button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                        <div class="lg:col-span-3">
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Pertanyaan</label>
                            <textarea name="questions[${uniqueId}][question_text]" rows="4" oninput="validateFormStates(); saveDraft()" class="q-text w-full rounded-lg border-[1.5px] border-gray-300 bg-gray-50 px-5 py-3 font-medium outline-none transition focus:border-blue-500 focus:bg-white dark:border-gray-600 dark:bg-gray-900 dark:text-white" required placeholder="Ketik butir soal di sini...">${data.text}</textarea>
                        </div>
                        <div class="lg:col-span-1 space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">CPMK</label>
                                <select name="questions[${uniqueId}][cpmk_id]" onchange="validateFormStates(); saveDraft()" class="q-cpmk w-full rounded-lg border-[1.5px] border-gray-300 bg-gray-50 px-5 py-3 font-medium outline-none focus:border-blue-500 focus:bg-white dark:border-gray-600 dark:bg-gray-900 dark:text-white" required>
                                    <option value="">-- Pilih --</option>
                                    ${cpmkOptions}
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Bobot (%)</label>
                                <input type="number" name="questions[${uniqueId}][weight]" value="${data.weight}" oninput="validateFormStates(); saveDraft()" class="q-weight w-full rounded-lg border-[1.5px] border-gray-300 bg-gray-50 px-5 py-3 font-medium outline-none focus:border-blue-500 focus:bg-white dark:border-gray-600 dark:bg-gray-900 dark:text-white" required placeholder="Cth: 20">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('questions-container').insertAdjacentHTML('beforeend', html);

        if (data.cpmk) { setTimeout(() => { document.querySelector(`#q-card-${uniqueId} .q-cpmk`).value = data.cpmk; validateFormStates(); }, 50); }

        updateQuestionNumbers();
        validateFormStates();
    }

    function removeCard(id) {
        const card = document.getElementById(`q-card-${id}`);
        if (card) card.remove();

        updateQuestionNumbers();
        validateFormStates();
        saveDraft();
        checkAutoAdd();
    }

    function updateQuestionNumbers() {
        const cards = document.querySelectorAll('.question-card');
        cards.forEach((card, index) => {
            const title = card.querySelector('.question-number-title');
            if (title) title.innerText = `Soal #${index + 1}`;
            const deleteBtn = card.querySelector('.delete-card-btn');
            if (deleteBtn) deleteBtn.style.display = cards.length === 1 ? 'none' : 'block';
        });
    }

    function validateFormStates() {
        let total = 0;
        let isLastCardFilled = true;
        const cards = document.querySelectorAll('.question-card');

        document.querySelectorAll('.q-weight').forEach(input => { total += Number(input.value) || 0; });

        if (cards.length > 0) {
            const lastCard = cards[cards.length - 1];
            const text = lastCard.querySelector('.q-text').value.trim();
            const cpmk = lastCard.querySelector('.q-cpmk').value;
            const weight = lastCard.querySelector('.q-weight').value;
            if (!text || !cpmk || !weight) isLastCardFilled = false;
        }

        const badge = document.getElementById('weight-badge');
        const actionContainer = document.getElementById('action-buttons-container');
        document.getElementById('weight-number').innerText = total;
        const btnSubmit = document.getElementById('btn-submit');

        clearTimeout(autoAddTimer);

        if (total === 100) {
            badge.className = 'inline-flex items-center rounded-full bg-green-100 px-4 py-2 text-sm font-bold text-green-800 border border-green-200 transition-colors';
            btnSubmit.disabled = false;
            actionContainer.style.display = 'none';
        } else {
            badge.className = total > 100 ? 'inline-flex items-center rounded-full bg-red-100 px-4 py-2 text-sm font-bold text-red-800 border border-red-200 transition-colors' : 'inline-flex items-center rounded-full bg-gray-100 px-4 py-2 text-sm font-bold text-gray-800 border border-gray-200 transition-colors';
            btnSubmit.disabled = true;
            actionContainer.style.display = 'flex';

            if (isLastCardFilled && total < 100) {
                autoAddTimer = setTimeout(() => {
                    let recheckTotal = 0;
                    document.querySelectorAll('.q-weight').forEach(input => { recheckTotal += Number(input.value) || 0; });
                    if (recheckTotal < 100) {
                        addQuestionCard();
                        setTimeout(() => {
                            const modalBody = document.getElementById('modal-body-scroll');
                            if (modalBody) modalBody.scrollTo({ top: modalBody.scrollHeight, behavior: 'smooth' });
                        }, 50);
                    }
                }, 800);
            }
        }
    }

    function checkAutoAdd() { validateFormStates(); }

    function saveDraft() {
        // Kalau lagi di mode Edit, jangan save ke draft
        const isEditMode = document.querySelector('[name="_method"]');
        if (!storageKey || isEditMode) return;

        let draftData = [];
        document.querySelectorAll('.question-card').forEach(card => {
            draftData.push({ text: card.querySelector('.q-text').value, cpmk: card.querySelector('.q-cpmk').value, weight: card.querySelector('.q-weight').value });
        });
        localStorage.setItem(storageKey, JSON.stringify(draftData));
    }

    function loadDraft() {
        if (!storageKey) return;
        const saved = localStorage.getItem(storageKey);
        if (saved) {
            const parsed = JSON.parse(saved);
            if (parsed.length > 0) { parsed.forEach(item => addQuestionCard(item)); return; }
        }
    }

    document.getElementById('form-pengajuan').addEventListener('submit', function () {
        if (storageKey) localStorage.removeItem(storageKey);
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
        border: 2px solid transparent;
        background-clip: content-box;
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #475569;
    }
</style>