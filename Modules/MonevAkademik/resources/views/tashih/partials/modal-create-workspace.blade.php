<template x-teleport="#modal-root">
    <div x-show="openCreate"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

        <div @click.away="openCreate = false"
            class="relative w-full max-w-5xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

            {{-- HEADER --}}
            <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                        <i class="fa-solid fa-file-circle-plus text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl"
                            x-text="isEditMode ? 'Edit Pengajuan Soal' : 'Buat Pengajuan Soal'"></h3>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="courseName"></p>
                    </div>
                </div>
                <div class="shrink-0">
                    <span id="weight-badge"
                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 sm:px-3 sm:py-1.5 text-[10px] sm:text-xs font-bold text-gray-800 border border-gray-200 transition-all duration-300 whitespace-nowrap shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                        Bobot: <span id="weight-number" class="ml-1">0</span>
                        <span class="mx-0.5 text-gray-400">/</span> 100
                    </span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar bg-slate-50 dark:bg-[#0f172a]" id="modal-body-scroll">
                <form id="form-pengajuan" class="flex flex-col min-h-full"
                    :action="isEditMode ? '{{ url('monev-akademik/tashih/update') }}/' + editUuid : '{{ route('monevakademik.tashih.store') }}'"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <template x-if="isEditMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <input type="hidden" name="course_id" :value="courseId">

                    <div class="p-4 sm:p-6 lg:p-8 flex-1 space-y-5">
                        {{-- SETUP UJIAN --}}
                        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fa-solid fa-clipboard-list text-indigo-500"></i> Informasi Ujian
                                </h4>
                            </div>
                            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Jenis Ujian</label>
                                    <select name="exam_type" required
                                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                        <option value="UTS">Ujian Tengah Semester (UTS)</option>
                                        <option value="UAS">Ujian Akhir Semester (UAS)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Periode Akademik</label>
                                    <select name="period_id" required
                                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                        <option value="">-- Pilih Periode --</option>
                                        @foreach($periods as $period)
                                            <option value="{{ $period->id }}" {{ $period->is_active ? 'selected' : '' }}>
                                                {{ $period->name }} - {{ $period->semester }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- BUTIR SOAL --}}
                        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                            <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fa-solid fa-list-ol text-indigo-500"></i> Butir Soal
                                </h4>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total bobot harus 100%</span>
                            </div>
                            <div class="p-5">
                                <div id="questions-container" class="space-y-4"></div>

                                <div id="action-buttons-container" class="mt-4 flex justify-center" style="display: none;">
                                    <button type="button" @click="openBankSoalModal()"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-dashed border-indigo-300 bg-indigo-50/50 px-6 py-3 text-sm font-bold text-indigo-600 hover:bg-indigo-50 transition w-full sm:w-auto dark:border-indigo-800/50 dark:bg-indigo-900/10 dark:text-indigo-400 dark:hover:bg-indigo-900/20">
                                        <i class="fas fa-database"></i> Cari dari Bank Soal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between sm:items-center">
                            <button type="button" @click="
                                if(isEditMode) {
                                    openCreate = false;
                                    openDetail = true;
                                } else {
                                    openCreate = false;
                                    $dispatch('buka-modal-matkul');
                                }
                            "
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 transition">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                            <button type="submit" id="btn-submit" disabled
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-8 py-2.5 text-sm font-bold text-white shadow-md hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400">
                                <i class="fas fa-paper-plane"></i>
                                <span x-text="isEditMode ? 'Simpan Perubahan' : 'Kirim Pengajuan'"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    let autoAddTimer = null;
    let autoRemoveTimer = null;
    const cpmkDataList = @json($cpmkList);

    function initCreateModal(cId, existingData = null) {
        activeCourseId = cId;
        storageKey = `draft_soal_${activeCourseId}`;
        clearTimeout(autoAddTimer);
        clearTimeout(autoRemoveTimer);

        const container = document.getElementById('questions-container');
        if (container) {
            container.innerHTML = '';
            if (existingData && existingData.length > 0) {
                existingData.forEach(q => addQuestionCard(q));
            } else {
                loadDraft();
                if (document.querySelectorAll('.question-card').length === 0) {
                    addQuestionCard();
                }
            }
            validateFormStates();
            updateQuestionNumbers();
        }
    }

    function addQuestionCard(data = { text: '', weight: '', cpmk: [], image_path: '' }) {
        const uniqueId = Date.now().toString(36) + Math.random().toString(36).substring(2, 8);

        let checkboxesHtml = cpmkDataList.map(cpmk => `
            <label class="flex items-center p-1.5 sm:p-2 rounded-lg hover:bg-indigo-50 cursor-pointer transition-colors dark:hover:bg-indigo-900/20">
                <input type="checkbox" name="questions[${uniqueId}][cpmk_id][]" value="${cpmk.id}"
                    onchange="validateFormStates(); saveDraft()"
                    class="q-cpmk-checkbox w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2 cursor-pointer dark:bg-gray-700 dark:border-gray-600">
                <span class="ml-2.5 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 select-none">${cpmk.id} - ${cpmk.name}</span>
            </label>
        `).join('');

        const html = `
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#0f172a] overflow-hidden question-card" id="q-card-${uniqueId}">
                <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/80 px-4 sm:px-5 py-3 dark:bg-[#1e293b] dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40">
                            <i class="fa-solid fa-circle-question text-xs text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <h5 class="font-bold text-gray-800 dark:text-gray-200 question-number-title text-sm">Soal #</h5>
                    </div>
                    <button type="button" onclick="removeCard('${uniqueId}')"
                        class="delete-card-btn inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white p-1.5 text-gray-400 hover:border-red-200 hover:bg-red-50 hover:text-red-600 transition dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-red-900/20"
                        title="Hapus Soal">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </div>

                <div class="p-4 sm:p-5 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Pertanyaan</label>
                        <textarea name="questions[${uniqueId}][question_text]" rows="3"
                            oninput="validateFormStates(); saveDraft()"
                            class="q-text w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white"
                            required placeholder="Ketik butir soal di sini...">${data.text}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 border-t border-gray-100 pt-4 dark:border-gray-700">
                        <div class="space-y-4">
                            <div>
                                <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Target CPMK</label>
                                <div class="max-h-36 overflow-y-auto custom-scrollbar rounded-xl border border-gray-200 bg-gray-50/50 p-2 dark:bg-[#0f172a] dark:border-gray-600 space-y-0.5">
                                    ${checkboxesHtml}
                                </div>
                                <p class="text-[10px] text-red-500 mt-1.5 font-medium hidden error-cpmk" id="err-cpmk-${uniqueId}">* Pilih minimal 1 CPMK</p>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Bobot (%)</label>
                                <div class="relative">
                                    <input type="number" name="questions[${uniqueId}][weight]" value="${data.weight}"
                                        oninput="validateFormStates(); saveDraft()"
                                        class="q-weight w-full rounded-xl border-gray-300 bg-gray-50 pl-4 pr-10 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white"
                                        required placeholder="0">
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 font-bold text-sm pointer-events-none">%</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Ilustrasi (Opsional)</label>
                            <div class="relative flex items-center justify-center min-h-[120px] w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 hover:border-indigo-400 hover:bg-indigo-50/30 transition-colors dark:border-gray-600 dark:bg-[#0f172a] p-3 text-center cursor-pointer group/file ${data.image_path ? 'hidden' : ''}" id="upload-wrapper-${uniqueId}">
                                <input type="file" name="questions[${uniqueId}][image]" accept="image/*"
                                    onchange="handleImagePreview(this, '${uniqueId}')"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="flex flex-col items-center justify-center gap-1">
                                    <i class="fas fa-image text-2xl text-gray-300 group-hover/file:text-indigo-500 transition-colors dark:text-gray-600"></i>
                                    <span class="text-xs font-medium text-gray-500 group-hover/file:text-indigo-600 dark:text-gray-400">Klik untuk menyisipkan gambar</span>
                                </div>
                            </div>
                            <div id="preview-container-${uniqueId}" class="relative rounded-xl border border-gray-200 bg-gray-50 p-2 ${data.image_path ? '' : 'hidden'} dark:bg-[#0f172a] dark:border-gray-600">
                                <img id="img-preview-${uniqueId}" src="${data.image_path ? '/storage/' + data.image_path : '#'}"
                                    class="w-full h-28 object-contain rounded-lg bg-white dark:bg-gray-900">
                                <button type="button" onclick="removeImage('${uniqueId}')"
                                    class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white shadow-md hover:bg-red-600 transition"
                                    title="Hapus Gambar">
                                    <i class="fas fa-times text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('questions-container').insertAdjacentHTML('beforeend', html);

        if (data.cpmk) {
            setTimeout(() => {
                let cpmkVals = Array.isArray(data.cpmk) ? data.cpmk : [data.cpmk];
                document.querySelectorAll(`#q-card-${uniqueId} .q-cpmk-checkbox`).forEach(cb => {
                    if (cpmkVals.includes(cb.value) || cpmkVals.includes(Number(cb.value))) {
                        cb.checked = true;
                    }
                });
                validateFormStates();
            }, 50);
        }

        updateQuestionNumbers();
        validateFormStates();
    }

    function handleImagePreview(input, id) {
        const container = document.getElementById(`preview-container-${id}`);
        const preview = document.getElementById(`img-preview-${id}`);
        const wrapper = document.getElementById(`upload-wrapper-${id}`);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
                wrapper.classList.add('hidden');
                validateFormStates();
                saveDraft();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage(id) {
        const input = document.querySelector(`#q-card-${id} input[type="file"]`);
        const container = document.getElementById(`preview-container-${id}`);
        const wrapper = document.getElementById(`upload-wrapper-${id}`);
        if (input) input.value = '';
        if (container) container.classList.add('hidden');
        if (wrapper) wrapper.classList.remove('hidden');
        validateFormStates();
        saveDraft();
    }

    function removeCard(id) {
        const card = document.getElementById(`q-card-${id}`);
        if (card) card.remove();
        updateQuestionNumbers();
        validateFormStates();
        saveDraft();
    }

    function updateQuestionNumbers() {
        const cards = document.querySelectorAll('.question-card');
        cards.forEach((card, index) => {
            const title = card.querySelector('.question-number-title');
            if (title) title.innerText = `Soal #${index + 1}`;
            const deleteBtn = card.querySelector('.delete-card-btn');
            if (deleteBtn) deleteBtn.style.display = cards.length === 1 ? 'none' : 'inline-flex';
        });
    }

    function validateFormStates() {
        let total = 0;
        let isAllCardsFilled = true;
        let cards = document.querySelectorAll('.question-card');

        document.querySelectorAll('.q-weight').forEach(input => { total += Number(input.value) || 0; });

        if (total >= 100) {
            let cardsArr = Array.from(cards);
            for (let i = cardsArr.length - 1; i > 0; i--) {
                let card = cardsArr[i];
                let text = card.querySelector('.q-text').value.trim();
                let weight = card.querySelector('.q-weight').value;
                if (!text && !weight) card.remove();
                else break;
            }
            cards = document.querySelectorAll('.question-card');
            total = 0;
            document.querySelectorAll('.q-weight').forEach(input => { total += Number(input.value) || 0; });
            updateQuestionNumbers();
        }

        cards.forEach(card => {
            const text = card.querySelector('.q-text').value.trim();
            const weight = card.querySelector('.q-weight').value;
            const checkedCpmks = card.querySelectorAll('.q-cpmk-checkbox:checked');
            const errCpmk = card.querySelector('.error-cpmk');
            if (checkedCpmks.length === 0) {
                isAllCardsFilled = false;
                if (errCpmk) errCpmk.classList.remove('hidden');
            } else if (errCpmk) {
                errCpmk.classList.add('hidden');
            }
            if (!text || !weight) isAllCardsFilled = false;
        });

        let isLastCardFilled = false;
        if (cards.length > 0) {
            const lastCard = cards[cards.length - 1];
            const text = lastCard.querySelector('.q-text').value.trim();
            const weight = lastCard.querySelector('.q-weight').value;
            const hasCpmk = lastCard.querySelectorAll('.q-cpmk-checkbox:checked').length > 0;
            if (text && weight && hasCpmk) isLastCardFilled = true;
        }

        const badge = document.getElementById('weight-badge');
        const actionContainer = document.getElementById('action-buttons-container');
        document.getElementById('weight-number').innerText = total;
        const btnSubmit = document.getElementById('btn-submit');

        clearTimeout(autoAddTimer);

        if (total === 100 && isAllCardsFilled) {
            badge.className = 'inline-flex items-center rounded-full bg-green-50 border border-green-200 px-2.5 py-1 sm:px-3 sm:py-1.5 text-[10px] sm:text-xs font-bold text-green-700 transition-all duration-300 whitespace-nowrap shadow-sm dark:bg-green-900/30 dark:border-green-800 dark:text-green-400';
            btnSubmit.disabled = false;
            actionContainer.style.display = 'none';
        } else {
            if (total > 100) {
                badge.className = 'inline-flex items-center rounded-full bg-red-50 border border-red-200 px-2.5 py-1 sm:px-3 sm:py-1.5 text-[10px] sm:text-xs font-bold text-red-700 transition-all duration-300 whitespace-nowrap shadow-sm dark:bg-red-900/30 dark:border-red-800 dark:text-red-400';
            } else {
                badge.className = 'inline-flex items-center rounded-full bg-amber-50 border border-amber-200 px-2.5 py-1 sm:px-3 sm:py-1.5 text-[10px] sm:text-xs font-bold text-amber-700 transition-all duration-300 whitespace-nowrap shadow-sm dark:bg-amber-900/30 dark:border-amber-800 dark:text-amber-400';
            }
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

        clearTimeout(autoRemoveTimer);
        autoRemoveTimer = setTimeout(() => {
            let currentCards = document.querySelectorAll('.question-card');
            let isDeleted = false;
            for (let i = 0; i < currentCards.length - 1; i++) {
                let card = currentCards[i];
                let text = card.querySelector('.q-text').value.trim();
                let weight = card.querySelector('.q-weight').value;
                let cpmksCount = card.querySelectorAll('.q-cpmk-checkbox:checked').length;
                let previewContainer = card.querySelector('[id^="preview-container-"]');
                let hasImage = previewContainer && !previewContainer.classList.contains('hidden');
                if (!text && !weight && cpmksCount === 0 && !hasImage) {
                    card.remove();
                    isDeleted = true;
                }
            }
            if (isDeleted) {
                updateQuestionNumbers();
                saveDraft();
                let newTotal = 0;
                document.querySelectorAll('.q-weight').forEach(input => { newTotal += Number(input.value) || 0; });
                document.getElementById('weight-number').innerText = newTotal;
            }
        }, 1000);
    }

    function saveDraft() {
        const isEditMode = document.querySelector('[name="_method"]');
        if (!storageKey || isEditMode) return;
        let draftData = [];
        document.querySelectorAll('.question-card').forEach(card => {
            let cpmkValues = [];
            card.querySelectorAll('.q-cpmk-checkbox:checked').forEach(cb => cpmkValues.push(cb.value));
            draftData.push({
                text: card.querySelector('.q-text').value,
                cpmk: cpmkValues,
                weight: card.querySelector('.q-weight').value,
            });
        });
        localStorage.setItem(storageKey, JSON.stringify(draftData));
    }

    function loadDraft() {
        if (!storageKey) return;
        const saved = localStorage.getItem(storageKey);
        if (saved) {
            const parsed = JSON.parse(saved);
            if (parsed.length > 0) {
                parsed.forEach(item => addQuestionCard(item));
            }
        }
    }

    document.getElementById('form-pengajuan').addEventListener('submit', function () {
        if (storageKey) localStorage.removeItem(storageKey);
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
</style>