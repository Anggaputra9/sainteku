@php
    $kampus = ($units ?? collect())->first(fn($u) => $u->id === $u->unit_parent);
    $kampusId = $kampus->id ?? 'UIN';
    $kampusName = $kampus->unit_name ?? 'UIN Prof. K.H. Saifuddin Zuhri';

    $fakultasList = ($units ?? collect())->filter(fn($u) => $u->unit_parent === $kampusId && $u->id !== $kampusId)->values();
    $listFakultasArr = $fakultasList->map(fn($f) => ['id' => $f->id, 'name' => $f->unit_name])->toArray();

    $fakultasIds = $fakultasList->pluck('id')->toArray();
    $prodiList = ($units ?? collect())->filter(fn($u) => in_array($u->unit_parent, $fakultasIds))->values();
    $listProdiArr = $prodiList->map(fn($p) => ['id' => $p->id, 'name' => $p->unit_name, 'parent' => $p->unit_parent])->toArray();
@endphp

<template x-teleport="#modal-root">
    <div x-data="openDocumentEditModal()" @open-edit-modal.window="handleOpenEdit($event)" x-show="openEdit"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

        <div @click.away="openEdit = false"
            class="relative w-full max-w-4xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

            <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                        <i class="fa-solid fa-pen-to-square text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl">Edit Dokumen</h3>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="editData.document_title || 'Perbarui informasi dokumen'"></p>
                    </div>
                </div>
                <div class="shrink-0 w-8 sm:w-9"></div>
            </div>

            <form :action="editUrl" method="POST" class="flex flex-col flex-1 min-h-0">
                @csrf
                @method('PUT')
                <input type="hidden" name="unit_id" x-model="editData.unit_id" required>
                <input type="hidden" name="sifat_dokumen" :value="editData.sifat_dokumen" required>

                <div class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a]">
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-file-lines text-indigo-500"></i> Informasi Dokumen
                            </h4>
                        </div>
                        <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Judul Dokumen <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="document_title" x-model="editData.document_title" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Tipe Dokumen <span class="text-red-500">*</span>
                                </label>
                                <select name="document_type_id" x-model="editData.document_type_id" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                    <option value="">-- Pilih Tipe --</option>
                                    @foreach ($documentTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-eye text-indigo-500"></i> Visibilitas Dokumen
                            </h4>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <button type="button" @click="editData.sifat_dokumen = 'Publik'"
                                    class="rounded-xl border p-4 text-left transition"
                                    :class="editData.sifat_dokumen === 'Publik'
                                        ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-500/20 dark:border-indigo-400 dark:bg-indigo-900/30'
                                        : 'border-gray-200 bg-gray-50 hover:border-gray-300 dark:border-gray-600 dark:bg-[#0f172a] dark:hover:border-gray-500'">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg"
                                            :class="editData.sifat_dokumen === 'Publik' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-800/50 dark:text-indigo-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'">
                                            <i class="fa-solid fa-globe"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">Public</p>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Semua pengguna dapat melihat dokumen ini.</p>
                                        </div>
                                    </div>
                                </button>
                                <button type="button" @click="editData.sifat_dokumen = 'Private'"
                                    class="rounded-xl border p-4 text-left transition"
                                    :class="editData.sifat_dokumen === 'Private'
                                        ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-500/20 dark:border-indigo-400 dark:bg-indigo-900/30'
                                        : 'border-gray-200 bg-gray-50 hover:border-gray-300 dark:border-gray-600 dark:bg-[#0f172a] dark:hover:border-gray-500'">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg"
                                            :class="editData.sifat_dokumen === 'Private' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-800/50 dark:text-indigo-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'">
                                            <i class="fa-solid fa-lock"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">Private</p>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hanya Anda yang dapat melihat dokumen ini.</p>
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-4 dark:border-amber-800/50 dark:bg-amber-900/20">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_ppid" value="1" x-model="editData.is_ppid"
                                        class="mt-1 h-5 w-5 rounded border-gray-300 text-amber-600 focus:ring-2 focus:ring-amber-500 dark:border-gray-600 dark:bg-gray-900">
                                    <div class="flex-1">
                                        <span class="block text-sm font-bold text-gray-900 dark:text-white">
                                            <i class="fa-solid fa-clipboard-check text-amber-600 dark:text-amber-400 mr-1"></i>
                                            Dokumen PPID
                                        </span>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                            Tandai jika dokumen termasuk kategori informasi publik PPID.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-calendar text-indigo-500"></i> Masa Berlaku
                            </h4>
                        </div>
                        <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Tanggal Berlaku <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="effective_date" x-model="editData.effective_date" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Tanggal Kadaluarsa</label>
                                <input type="date" name="expired_date" x-model="editData.expired_date"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 flex flex-col-reverse gap-3 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 dark:bg-[#1e293b]/95 dark:border-gray-700 sm:flex-row sm:justify-end">
                    <button type="button" @click="openEdit = false"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        <i class="fas fa-xmark"></i> Batal
                    </button>
                    <button type="submit"
                        class="inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-indigo-700 transition">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('openDocumentEditModal', () => ({
            openEdit: false,
            editUrl: '',
            editData: {
                document_title: '',
                document_type_id: '',
                unit_id: '',
                effective_date: '',
                expired_date: '',
                sifat_dokumen: 'Private',
                is_ppid: false,
            },

            handleOpenEdit(event) {
                const doc = event.detail.doc || {};
                this.editUrl = doc.update_url || '';
                this.editData = {
                    document_title: doc.document_title || '',
                    document_type_id: doc.document_type_id || '',
                    unit_id: doc.unit_id || '',
                    effective_date: doc.effective_date || '',
                    expired_date: doc.expired_date || '',
                    sifat_dokumen: doc.sifat_dokumen || 'Private',
                    is_ppid: !!doc.is_ppid,
                };
                this.openEdit = true;
            },
        }));
    });
</script>