<template x-teleport="#modal-root">
    <div x-show="openCreate"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
        @click.self="!openBankSoal && (openCreate = false)"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

        <div
            class="relative w-full max-w-5xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

            {{-- HEADER --}}
            <div class="shrink-0 border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 sticky top-0">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3">
                        <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-50 border border-indigo-100 dark:bg-indigo-900/30 dark:border-indigo-800/50">
                            <i class="fa-solid fa-file-circle-plus text-sm text-indigo-600 dark:text-indigo-400 sm:text-base"></i>
                        </div>
                        <div class="min-w-0 leading-tight">
                            <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl"
                                x-text="isEditMode ? 'Edit Pengajuan Soal' : 'Buat Pengajuan Soal'"></h3>
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="courseName"></p>
                        </div>
                    </div>
                    <span id="weight-badge"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 sm:px-3 sm:py-1.5 text-[10px] sm:text-xs font-bold text-slate-600 border border-slate-200 transition-all duration-300 whitespace-nowrap dark:bg-slate-800 dark:border-slate-600 dark:text-slate-300">
                        <i class="fa-solid fa-scale-balanced text-[10px] opacity-60"></i>
                        <span id="weight-number">0</span><span class="text-slate-400">/100</span>
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

                        {{-- INFORMASI UJIAN --}}
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
                                        class="w-full rounded-xl border border-gray-200 bg-slate-50 px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-indigo-400 focus:bg-white dark:border-gray-600 dark:bg-[#0f172a] dark:text-white dark:focus:border-indigo-500">
                                        <option value="UTS">Ujian Tengah Semester (UTS)</option>
                                        <option value="UAS">Ujian Akhir Semester (UAS)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Periode Akademik</label>
                                    <select name="period_id" required
                                        class="w-full rounded-xl border border-gray-200 bg-slate-50 px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-indigo-400 focus:bg-white dark:border-gray-600 dark:bg-[#0f172a] dark:text-white dark:focus:border-indigo-500">
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
                            <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between gap-3">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fa-solid fa-list-ol text-indigo-500"></i> Butir Soal
                                </h4>
                                <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-500">Total bobot 100%</span>
                            </div>
                            <div class="p-5">
                                <div id="questions-container" class="space-y-4"></div>

                                <div id="action-buttons-container" class="mt-4 flex justify-center" style="display: none;">
                                    <button type="button" @click="openBankSoalModal()"
                                        class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl border border-dashed border-gray-300 bg-slate-50 px-6 py-3 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-50/50 dark:border-gray-600 dark:bg-[#0f172a] dark:text-indigo-400 dark:hover:border-indigo-700 dark:hover:bg-indigo-900/10">
                                        <i class="fas fa-database"></i> Cari dari Bank Soal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                        <div class="flex flex-row flex-nowrap items-center justify-between gap-2 sm:gap-4">
                            <button type="button" @click="
                                if(isEditMode) {
                                    openCreate = false;
                                    openDetail = true;
                                } else {
                                    openCreate = false;
                                    $dispatch('buka-modal-matkul');
                                }
                            "
                                class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                            <button type="submit" id="btn-submit" disabled
                                class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed sm:px-8 sm:py-2.5 sm:text-sm transition-all">
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

{{-- MODAL PREVIEW GAMBAR SOAL --}}
<template x-teleport="#modal-root">
    <div id="question-image-preview-modal"
        class="app-modal-overlay fixed inset-0 hidden items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/50 p-3 sm:p-6"
        style="z-index: 10000002 !important;"
        onclick="if (event.target === this) closeQuestionImagePreview()">

        <div class="relative w-full max-w-3xl flex flex-col max-h-[90dvh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 overflow-hidden">

            <div class="shrink-0 border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 dark:bg-[#1e293b] dark:border-gray-700">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-50 border border-indigo-100 dark:bg-indigo-900/30 dark:border-indigo-800/50">
                        <i class="fa-solid fa-image text-sm text-indigo-600 dark:text-indigo-400 sm:text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-lg">Preview Ilustrasi</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pratinjau gambar butir soal</p>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 bg-slate-50 dark:bg-[#0f172a]">
                <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-4 dark:border-gray-700 dark:bg-[#1e293b]">
                    <img id="question-image-preview-img" src="#" alt="Preview ilustrasi soal"
                        class="mx-auto max-h-[55vh] w-full object-contain rounded-lg">
                </div>
            </div>

            <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 dark:bg-[#1e293b]/95 dark:border-gray-700">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2 sm:gap-4">
                    <button type="button" onclick="closeQuestionImagePreview()"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                    <button type="button" onclick="removeImageFromPreviewModal()"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-red-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-red-700 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    let autoAddTimer = null;
    let autoRemoveTimer = null;
    let activePreviewImageId = null;
    let cpmkDataList = [];

    async function loadCpmkForCourse(courseId) {
        try {
            const response = await fetch(`{{ url('monev-akademik/tashih/api/cpmk') }}/${courseId}`, {
                headers: { 'Accept': 'application/json' },
            });

            if (response.ok) {
                cpmkDataList = await response.json();
            } else {
                cpmkDataList = [];
            }
        } catch (error) {
            console.error('Gagal memuat CPMK', error);
            cpmkDataList = [];
        }
    }

    async function initCreateModal(cId, existingData = null) {
        activeCourseId = cId;
        storageKey = `draft_soal_${activeCourseId}`;
        clearTimeout(autoAddTimer);
        clearTimeout(autoRemoveTimer);

        await loadCpmkForCourse(cId);

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

        let checkboxesHtml = cpmkDataList.length > 0
            ? cpmkDataList.map(cpmk => `
            <label class="flex items-start gap-2.5 rounded-lg px-2 py-1.5 cursor-pointer transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                <input type="checkbox" name="questions[${uniqueId}][cpmk_id][]" value="${cpmk.id}"
                    onchange="validateFormStates(); saveDraft()"
                    class="q-cpmk-checkbox mt-0.5 h-4 w-4 shrink-0 rounded border-gray-300 text-indigo-600 focus:ring-0 focus:ring-offset-0 dark:border-gray-600 dark:bg-gray-700">
                <span class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-300 leading-snug">${cpmk.id} — ${cpmk.name}</span>
            </label>
        `).join('')
            : `<p class="px-2 py-3 text-xs text-amber-700 dark:text-amber-300">Belum ada CPMK untuk mata kuliah ini. Hubungi admin untuk menambahkan CPMK di Master Data.</p>`;

        const html = `
            <div class="question-card rounded-xl border border-gray-200 bg-slate-50/50 overflow-hidden dark:border-gray-700 dark:bg-[#0f172a]/50" id="q-card-${uniqueId}">
                <div class="flex items-center justify-between bg-white px-4 py-3 border-b border-gray-100 dark:bg-[#1e293b] dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <span class="question-number-badge flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-600 text-xs font-bold text-white shadow-sm">1</span>
                        <span class="question-number-title text-sm font-bold text-gray-700 dark:text-gray-200">Butir Soal</span>
                    </div>
                    <button type="button" onclick="removeCard('${uniqueId}')"
                        class="delete-card-btn inline-flex items-center justify-center rounded-lg p-1.5 text-gray-400 transition hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20"
                        title="Hapus Soal">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </div>

                <div class="p-4 sm:p-5 space-y-4 bg-white dark:bg-[#1e293b]">
                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Pertanyaan</label>
                        <textarea name="questions[${uniqueId}][question_text]" rows="3"
                            oninput="validateFormStates(); saveDraft()"
                            class="q-text w-full rounded-xl border border-gray-200 bg-slate-50 px-3 py-2.5 text-sm text-gray-800 outline-none transition resize-y focus:border-indigo-400 focus:bg-white dark:border-gray-600 dark:bg-[#0f172a] dark:text-white dark:focus:border-indigo-500"
                            required placeholder="Ketik butir soal di sini...">${data.text}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div>
                            <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Target CPMK</label>
                            <div class="max-h-48 overflow-y-auto custom-scrollbar rounded-xl border border-gray-200 bg-slate-50 p-2 space-y-0.5 dark:border-gray-600 dark:bg-[#0f172a]">
                                ${checkboxesHtml}
                            </div>
                            <p class="text-[10px] text-red-500 mt-1.5 font-medium hidden error-cpmk" id="err-cpmk-${uniqueId}">Pilih minimal 1 CPMK</p>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div>
                                <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Bobot (%)</label>
                                <div class="relative">
                                    <input type="number" name="questions[${uniqueId}][weight]" value="${data.weight}"
                                        oninput="validateFormStates(); saveDraft()"
                                        class="q-weight w-full rounded-xl border border-gray-200 bg-slate-50 px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-indigo-400 focus:bg-white dark:border-gray-600 dark:bg-[#0f172a] dark:text-white dark:focus:border-indigo-500 pr-10"
                                        required placeholder="0" min="0" max="100">
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm font-semibold text-gray-400 pointer-events-none">%</span>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Ilustrasi <span class="normal-case font-medium text-gray-300">(opsional)</span></label>
                                <div class="relative flex items-center justify-center min-h-[88px] w-full rounded-xl border border-dashed border-gray-300 bg-slate-50 p-3 text-center cursor-pointer transition hover:border-indigo-300 hover:bg-indigo-50/30 dark:border-gray-600 dark:bg-[#0f172a] dark:hover:border-indigo-700 ${data.image_path ? 'hidden' : ''}" id="upload-wrapper-${uniqueId}">
                                    <input type="file" name="questions[${uniqueId}][image]" accept="image/*"
                                        onchange="handleImagePreview(this, '${uniqueId}')"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    <div class="flex flex-col items-center gap-1 pointer-events-none">
                                        <i class="fas fa-image text-xl text-gray-300 dark:text-gray-600"></i>
                                        <span class="text-xs text-gray-400">Klik untuk upload gambar</span>
                                    </div>
                                </div>
                                <div id="image-actions-${uniqueId}" class="${data.image_path ? 'flex' : 'hidden'} items-center gap-2 rounded-xl border border-gray-200 bg-slate-50 px-3 py-2.5 dark:border-gray-600 dark:bg-[#0f172a]">
                                    <span class="min-w-0 flex-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-check-circle text-emerald-500 mr-1"></i>Gambar terlampir
                                    </span>
                                    <button type="button" onclick="openQuestionImagePreview('${uniqueId}')"
                                        class="inline-flex shrink-0 items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-indigo-700 transition">
                                        <i class="fas fa-eye"></i> Preview
                                    </button>
                                </div>
                                <img id="img-preview-${uniqueId}" src="${data.image_path ? '/storage/' + data.image_path : '#'}" alt="" class="hidden" aria-hidden="true">
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
        const preview = document.getElementById(`img-preview-${id}`);
        const wrapper = document.getElementById(`upload-wrapper-${id}`);
        const actions = document.getElementById(`image-actions-${id}`);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                wrapper.classList.add('hidden');
                if (actions) {
                    actions.classList.remove('hidden');
                    actions.classList.add('flex');
                }
                validateFormStates();
                saveDraft();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openQuestionImagePreview(id) {
        const preview = document.getElementById(`img-preview-${id}`);
        const modal = document.getElementById('question-image-preview-modal');
        const modalImg = document.getElementById('question-image-preview-img');
        if (!preview || !modal || !modalImg || !preview.src || preview.src === '#') return;

        activePreviewImageId = id;
        modalImg.src = preview.src;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeQuestionImagePreview() {
        const modal = document.getElementById('question-image-preview-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        activePreviewImageId = null;
    }

    function removeImageFromPreviewModal() {
        if (activePreviewImageId) {
            removeImage(activePreviewImageId);
        }
        closeQuestionImagePreview();
    }

    function removeImage(id) {
        const input = document.querySelector(`#q-card-${id} input[type="file"]`);
        const preview = document.getElementById(`img-preview-${id}`);
        const actions = document.getElementById(`image-actions-${id}`);
        const wrapper = document.getElementById(`upload-wrapper-${id}`);
        if (input) input.value = '';
        if (preview) preview.src = '#';
        if (actions) {
            actions.classList.add('hidden');
            actions.classList.remove('flex');
        }
        if (wrapper) wrapper.classList.remove('hidden');
        if (activePreviewImageId === id) closeQuestionImagePreview();
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
            const badge = card.querySelector('.question-number-badge');
            if (badge) badge.innerText = index + 1;
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
            badge.className = 'inline-flex shrink-0 items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 sm:px-3 sm:py-1.5 text-[10px] sm:text-xs font-bold text-emerald-700 border border-emerald-200 transition-all duration-300 whitespace-nowrap dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-400';
            btnSubmit.disabled = false;
            actionContainer.style.display = 'none';
        } else {
            if (total > 100) {
                badge.className = 'inline-flex shrink-0 items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 sm:px-3 sm:py-1.5 text-[10px] sm:text-xs font-bold text-red-700 border border-red-200 transition-all duration-300 whitespace-nowrap dark:bg-red-900/20 dark:border-red-800 dark:text-red-400';
            } else {
                badge.className = 'inline-flex shrink-0 items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 sm:px-3 sm:py-1.5 text-[10px] sm:text-xs font-bold text-amber-700 border border-amber-200 transition-all duration-300 whitespace-nowrap dark:bg-amber-900/20 dark:border-amber-800 dark:text-amber-400';
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
                let imageActions = card.querySelector('[id^="image-actions-"]');
                let hasImage = imageActions && !imageActions.classList.contains('hidden');
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