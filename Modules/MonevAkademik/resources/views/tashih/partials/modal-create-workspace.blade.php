<div x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

    <div
        class="relative w-full max-w-5xl transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-gray-700 transition-all flex flex-col max-h-[90dvh] sm:max-h-[95vh] overflow-hidden">

        {{-- HEADER MODAL (Didesain ulang biar sejajar 1 baris di HP) --}}
        <div
            class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-3 sm:px-6 py-3 sm:py-4 z-20 dark:bg-gray-800 dark:border-gray-700 shadow-sm gap-2">

            <div class="flex items-center gap-2 sm:gap-4 flex-1 overflow-hidden">
                <button @click="
                            if(isEditMode) { 
                                openCreate = false; 
                                openDetail = true; 
                            } else { 
                                openCreate = false; 
                                $dispatch('buka-modal-matkul'); 
                            }
                        " type="button" class="shrink-0 inline-flex items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-all dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    title="Kembali">
                    <i class="fas fa-arrow-left text-lg sm:text-xl"></i>
                </button>

                <div class="flex-1 overflow-hidden pr-2">
                    <h3
                        class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 leading-tight truncate">
                        <span x-text="isEditMode ? 'Edit Pengajuan' : 'Workspace Soal'"></span>
                    </h3>
                    <p class="text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 mt-0.5 truncate"
                        x-text="courseId + ' - ' + courseName"></p>
                </div>
            </div>

            <div class="shrink-0">
                <span id="weight-badge"
                    class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 sm:px-3 sm:py-1.5 text-[10px] sm:text-xs font-bold text-gray-800 border border-gray-200 transition-all duration-300 whitespace-nowrap shadow-sm">
                    Bobot: <span id="weight-number" class="ml-1 text-xs sm:text-sm">0</span> <span
                        class="mx-0.5 text-gray-400">/</span> 100
                </span>
            </div>
        </div>

        {{-- Body Area --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar relative bg-slate-50 dark:bg-gray-900"
            id="modal-body-scroll">
            <form id="form-pengajuan" class="flex flex-col min-h-full"
                :action="isEditMode ? '{{ url('monev-akademik/tashih/update') }}/' + editUuid : '{{ route('monevakademik.tashih.store') }}'"
                method="POST" enctype="multipart/form-data">
                @csrf
                <template x-if="isEditMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <input type="hidden" name="course_id" :value="courseId">

                {{-- Kontainer Konten --}}
                <div class="p-3 sm:p-6 lg:p-8 flex-1">
                    {{-- Section Setup --}}
                    <div
                        class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 mb-5 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label
                                    class="mb-1.5 block text-xs sm:text-sm font-semibold text-gray-900 dark:text-gray-200">Jenis
                                    Ujian</label>
                                <select name="exam_type"
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50/50 px-3 py-2 sm:px-4 sm:py-2.5 text-sm font-medium text-gray-900 outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                    required>
                                    <option value="UTS">Ujian Tengah Semester (UTS)</option>
                                    <option value="UAS">Ujian Akhir Semester (UAS)</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="mb-1.5 block text-xs sm:text-sm font-semibold text-gray-900 dark:text-gray-200">Periode
                                    Akademik</label>
                                <select name="period_id"
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50/50 px-3 py-2 sm:px-4 sm:py-2.5 text-sm font-medium text-gray-900 outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                    required>
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

                    <div id="questions-container" class="space-y-4 sm:space-y-6"></div>

                    {{-- Area Aksi Tambah Soal (Hanya Bank Soal) --}}
                    <div id="action-buttons-container" class="mt-4 flex justify-center" style="display: none;">
                        <button type="button" @click="openBankSoalModal()"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-white border border-indigo-200 border-dashed px-6 py-2.5 text-xs sm:text-sm font-bold text-indigo-600 hover:bg-indigo-50 transition w-full sm:w-1/2 shadow-sm dark:bg-gray-800 dark:border-indigo-800/50 dark:text-indigo-400 dark:hover:bg-gray-700">
                            <i class="fas fa-search"></i> Cari dari Bank Soal
                        </button>
                    </div>
                </div>

                {{-- Sticky Footer --}}
                <div
                    class="sticky bottom-0 w-full bg-white/95 backdrop-blur-md border-t border-gray-200 px-4 py-3 sm:px-8 sm:py-4 z-20 flex justify-end dark:border-gray-700 dark:bg-gray-800/95 shrink-0">
                    <button type="submit" id="btn-submit" disabled
                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-green-600 px-6 py-2.5 sm:px-8 sm:py-2.5 text-sm font-bold text-white shadow-md hover:bg-green-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400 w-full sm:w-auto">
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
    let autoRemoveTimer = null;

    // GANTI KE JSON BIAR BISA DI-LOOP BIKIN CHECKBOX
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

        // Generate Checkbox HTML 
        let checkboxesHtml = cpmkDataList.map(cpmk => `
            <label class="flex items-center p-1.5 sm:p-2 rounded hover:bg-gray-100 cursor-pointer transition-colors dark:hover:bg-gray-800">
                <input type="checkbox" name="questions[${uniqueId}][cpmk_id][]" value="${cpmk.id}" 
                    onchange="validateFormStates(); saveDraft()" 
                    class="q-cpmk-checkbox w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800">
                <span class="ml-2.5 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 select-none">${cpmk.id} - ${cpmk.name}</span>
            </label>
        `).join('');

        const html = `
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow duration-300 dark:border-gray-700 dark:bg-gray-800 overflow-hidden question-card group" id="q-card-${uniqueId}">
                <div class="bg-slate-50/80 border-b border-gray-100 px-4 sm:px-6 py-2.5 sm:py-3 flex justify-between items-center dark:bg-gray-800/80 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-blue-500"></div>
                        <h5 class="font-bold text-gray-700 dark:text-gray-200 question-number-title text-xs sm:text-sm">Soal #</h5>
                    </div>
                    <button type="button" onclick="removeCard('${uniqueId}')" class="text-gray-400 hover:text-red-600 bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 rounded-md p-1.5 transition-all shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-red-900/30 dark:hover:border-red-800 delete-card-btn" title="Hapus Soal">
                        <i class="fas fa-trash-alt text-[10px] sm:text-xs w-4 h-4 flex items-center justify-center"></i>
                    </button>
                </div>
                
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col gap-4 sm:gap-6">
                        <div>
                            <label class="mb-1.5 block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pertanyaan</label>
                            <textarea name="questions[${uniqueId}][question_text]" rows="3" oninput="validateFormStates(); saveDraft()" class="q-text w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 sm:px-4 sm:py-3 text-xs sm:text-sm font-medium text-gray-900 outline-none transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-gray-600 dark:bg-gray-900 dark:text-white" required placeholder="Ketik butir soal di sini...">${data.text}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8 items-start border-t border-gray-100 pt-4 sm:pt-5 dark:border-gray-700">
                            <div class="flex flex-col gap-4 sm:gap-5">
                                <div>
                                    <label class="mb-1.5 block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Multi CPMK</label>
                                    <div class="space-y-0.5 sm:space-y-1 max-h-32 sm:max-h-36 overflow-y-auto custom-scrollbar p-1.5 sm:p-2 border border-gray-300 rounded-lg bg-gray-50/50 dark:bg-gray-900 dark:border-gray-600">
                                        ${checkboxesHtml}
                                    </div>
                                    <p class="text-[10px] text-red-500 mt-1.5 font-medium hidden error-cpmk" id="err-cpmk-${uniqueId}">* Pilih minimal 1 CPMK</p>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Bobot (%)</label>
                                    <div class="relative">
                                        <input type="number" name="questions[${uniqueId}][weight]" value="${data.weight}" oninput="validateFormStates(); saveDraft()" class="q-weight w-full rounded-lg border border-gray-300 bg-white pl-3 pr-8 sm:pl-4 sm:pr-10 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-gray-900 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all dark:border-gray-600 dark:bg-gray-900 dark:text-white" required placeholder="0">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 sm:pr-4 pointer-events-none text-gray-400 font-bold text-xs sm:text-sm">%</div>
                                    </div>
                                </div>
                            </div>

                            <div class="h-full flex flex-col">
                                <label class="mb-1.5 block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Ilustrasi (Opsional)</label>
                                
                                <div class="relative flex-1 flex items-center justify-center min-h-[80px] sm:min-h-[100px] w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-blue-50 hover:border-blue-400 transition-colors dark:border-gray-600 dark:bg-gray-800/50 p-2 text-center cursor-pointer group/file ${data.image_path ? 'hidden' : ''}" id="upload-wrapper-${uniqueId}">
                                    <input type="file" name="questions[${uniqueId}][image]" accept="image/*" 
                                        onchange="handleImagePreview(this, '${uniqueId}')"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="flex flex-col items-center justify-center space-y-1">
                                        <i class="fas fa-image text-gray-400 group-hover/file:text-blue-500 text-xl sm:text-2xl mb-1 transition-colors"></i>
                                        <span class="text-[10px] sm:text-[11px] font-medium text-gray-500 group-hover/file:text-blue-600">Klik untuk menyisipkan gambar</span>
                                    </div>
                                </div>

                                <div id="preview-container-${uniqueId}" class="relative h-full flex flex-col justify-center rounded-lg border border-gray-200 bg-slate-50 p-2 shadow-sm ${data.image_path ? '' : 'hidden'} dark:bg-gray-800 dark:border-gray-600">
                                    <img id="img-preview-${uniqueId}" src="${data.image_path ? '/storage/' + data.image_path : '#'}" 
                                        class="w-full h-20 sm:h-24 object-contain rounded bg-white dark:bg-gray-900">
                                    <button type="button" onclick="removeImage('${uniqueId}')" class="absolute -top-2 -right-2 w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center rounded-full bg-red-500 text-white shadow-md hover:bg-red-600 transition-transform hover:scale-110" title="Hapus Gambar">
                                        <i class="fas fa-times text-[8px] sm:text-[10px]"></i>
                                    </button>
                                </div>
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
                const checkboxes = document.querySelectorAll(`#q-card-${uniqueId} .q-cpmk-checkbox`);

                checkboxes.forEach(cb => {
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
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage(id) {
        const input = document.querySelector(`#q-card-${id} input[type="file"]`);
        const container = document.getElementById(`preview-container-${id}`);
        const wrapper = document.getElementById(`upload-wrapper-${id}`);

        if (input) input.value = "";
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
            if (deleteBtn) deleteBtn.style.display = cards.length === 1 ? 'none' : 'block';
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

                if (!text && !weight) {
                    card.remove();
                } else {
                    break;
                }
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
            } else {
                if (errCpmk) errCpmk.classList.add('hidden');
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
            badge.className = 'inline-flex items-center rounded-full bg-green-100 border border-green-200 px-2.5 py-1 sm:px-3 sm:py-1.5 text-[10px] sm:text-xs font-bold text-green-800 transition-all duration-300 whitespace-nowrap shadow-sm';
            btnSubmit.disabled = false;
            actionContainer.style.display = 'none';
        } else {
            if (total > 100) {
                badge.className = 'inline-flex items-center rounded-full bg-red-100 border border-red-200 px-2.5 py-1 sm:px-3 sm:py-1.5 text-[10px] sm:text-xs font-bold text-red-800 transition-all duration-300 whitespace-nowrap shadow-sm';
            } else {
                badge.className = 'inline-flex items-center rounded-full bg-amber-100 border border-amber-200 px-2.5 py-1 sm:px-3 sm:py-1.5 text-[10px] sm:text-xs font-bold text-amber-800 transition-all duration-300 whitespace-nowrap shadow-sm';
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
            const checkboxes = card.querySelectorAll('.q-cpmk-checkbox:checked');
            checkboxes.forEach(cb => {
                cpmkValues.push(cb.value);
            });

            draftData.push({
                text: card.querySelector('.q-text').value,
                cpmk: cpmkValues,
                weight: card.querySelector('.q-weight').value
            });
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