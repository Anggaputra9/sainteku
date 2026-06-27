@php
    $kampus = ($units ?? collect())->first(fn($u) => $u->id === $u->unit_parent);
    $kampusId = $kampus->id ?? 'U001';
    $kampusName = $kampus->unit_name ?? 'UIN Prof. K.H. Saifuddin Zuhri';

    $fakultasList = ($units ?? collect())->filter(fn($u) => $u->unit_parent === $kampusId && $u->id !== $kampusId)->values();
    $listFakultasArr = $fakultasList->map(fn($f) => ['id' => $f->id, 'name' => $f->unit_name])->toArray();

    $fakultasIds = $fakultasList->pluck('id')->toArray();
    $prodiList = ($units ?? collect())->filter(fn($u) => in_array($u->unit_parent, $fakultasIds))->values();
    $listProdiArr = $prodiList->map(fn($p) => ['id' => $p->id, 'name' => $p->unit_name, 'parent' => $p->unit_parent])->toArray();
@endphp

{{-- MODAL EDIT DOKUMEN --}}
<div x-show="openEdit"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openEdit = false" x-show="openEdit"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="flex flex-col w-full max-w-4xl max-h-[90vh] rounded-2xl bg-white shadow-2xl dark:bg-gray-800 overflow-hidden">

        {{-- Header Modal --}}
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Dokumen</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui informasi dokumen</p>
            </div>
        </div>

        {{-- Form Area --}}
        <form :action="editUrl" method="POST" class="flex flex-col flex-1 min-h-0">
            @csrf
            @method('PUT')
            <input type="hidden" name="unit_id" x-model="editData.unit_id" required>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
            <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                {{-- Judul Dokumen (Full Width) --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Judul Dokumen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="document_title" x-model="editData.document_title" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Contoh: SK Rektor Tahun 2026 tentang Akademik">
                </div>

                {{-- Tipe Dokumen --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tipe Dokumen <span class="text-red-500">*</span>
                    </label>
                    <select name="document_type_id" x-model="editData.document_type_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Tipe --</option>
                        @foreach ($documentTypes as $type)
                            <option value="{{ $type->id }}">
                                {{ $type->description }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Sifat Dokumen --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Sifat Dokumen <span class="text-red-500">*</span>
                    </label>
                    <select name="sifat_dokumen" x-model="editData.sifat_dokumen" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Sifat --</option>
                        <option value="Publik">
                            Publik (Dapat dilihat semua orang)
                        </option>
                        <option value="Private">
                            Private (Terbatas/Rahasia)
                        </option>
                    </select>
                </div>

                {{-- Ceklist PPID --}}
                <div class="md:col-span-2">
                    <div class="rounded-xl bg-amber-50/50 p-4 ring-1 ring-amber-100 dark:bg-amber-900/10 dark:ring-amber-900/30">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="is_ppid" value="1" x-model="editData.is_ppid"
                                class="mt-1 h-5 w-5 rounded border-gray-300 text-amber-600 focus:ring-2 focus:ring-amber-500 dark:border-gray-600 dark:bg-gray-900">
                            <div class="flex-1">
                                <span class="block text-sm font-bold text-gray-900 dark:text-white">
                                    <i class="fa-solid fa-clipboard-check text-amber-600 dark:text-amber-400 mr-1"></i>
                                    Dokumen PPID
                                </span>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    Pejabat Pengelola Informasi dan Dokumentasi
                                </p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Unit Pemilik --}}
                <div class="rounded-xl bg-blue-50/50 p-4 ring-1 ring-blue-100 dark:bg-blue-900/10 dark:ring-blue-900/30 md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Unit Pemilik <span class="text-red-500">*</span>
                    </label>
                    <select name="unit_id" x-model="editData.unit_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Unit --</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">
                                {{ $unit->unit_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Area Tanggal (Box Khusus) --}}
                <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 md:col-span-2">
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-widest text-gray-400 italic">Masa Berlaku
                        Dokumen</h4>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        {{-- Tanggal Berlaku --}}
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tanggal
                                Berlaku <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i
                                        class="fa-regular fa-calendar"></i></span>
                                <input type="text" name="effective_date" x-model="editData.effective_date" required
                                    placeholder="Pilih Tanggal..." x-init="flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd F Y', allowInput: false, static: true })"
                                    class="w-full rounded-lg border-0 py-2.5 pl-10 pr-4 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 dark:text-white dark:ring-gray-600 cursor-pointer">
                            </div>
                        </div>

                        {{-- Tanggal Kadaluarsa --}}
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tanggal
                                Kadaluarsa</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i
                                        class="fa-regular fa-calendar"></i></span>
                                <input type="text" name="expired_date" x-model="editData.expired_date"
                                    placeholder="Pilih Tanggal..." x-init="flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd F Y', allowInput: false, static: true })"
                                    class="w-full rounded-lg border-0 py-2.5 pl-10 pr-4 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 dark:text-white dark:ring-gray-600 cursor-pointer">
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            </div>

            {{-- Tombol Aksi Bawah --}}
            <div
                class="shrink-0 flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800/80 sm:flex-row sm:justify-end">
                <button type="button" @click="openEdit = false"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    <i class="fas fa-xmark"></i>
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
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
