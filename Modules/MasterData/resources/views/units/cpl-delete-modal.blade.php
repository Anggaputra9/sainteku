<template x-teleport="#modal-root">
    <div x-data="cplDeleteModal()" @open-cpl-delete-modal.window="handleOpen($event)" x-show="open"
        @click.self="close()"
        class="app-modal-overlay fixed inset-0 z-[10000002] flex items-center justify-center p-3 sm:p-6 overflow-y-auto backdrop-blur-sm bg-gray-900/50"
        x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>

        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 overflow-hidden">

            <div class="border-b border-gray-200 bg-white px-4 py-3 sm:px-6 sm:py-4 dark:bg-[#1e293b] dark:border-gray-700">
                <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-red-100 bg-red-50 dark:border-red-800/50 dark:bg-red-900/30">
                        <i class="fa-solid fa-triangle-exclamation text-sm text-red-600 dark:text-red-400 sm:text-base"></i>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-lg">Konfirmasi Hapus CPL</h3>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="subtitle"></p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">
                <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300" x-text="message"></p>

                <div x-show="mode === 'bulk' && items.length > 0" class="max-h-36 overflow-y-auto rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-[#1e293b] custom-scrollbar">
                    <template x-for="(item, index) in items" :key="item.id">
                        <div class="flex items-start gap-3 border-b border-gray-100 px-3 py-2.5 last:border-b-0 dark:border-gray-700/60">
                            <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gray-100 text-[10px] font-bold text-gray-500 dark:bg-gray-800 dark:text-gray-400" x-text="index + 1"></span>
                            <div class="min-w-0 flex-1">
                                <span class="font-bold font-mono text-xs text-teal-700 dark:text-teal-300" x-text="item.id"></span>
                                <p x-show="item.name" class="mt-0.5 text-xs leading-snug text-gray-600 dark:text-gray-400" x-text="item.name"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex flex-row flex-nowrap items-center justify-end gap-2 pt-2">
                    <button type="button" @click="close()" :disabled="deleting"
                        class="inline-flex shrink-0 items-center justify-center rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 disabled:opacity-60 dark:bg-gray-700 dark:text-gray-200 sm:px-5 sm:py-2.5 sm:text-sm transition">
                        Batal
                    </button>
                    <button type="button" @click="confirmDelete()" :disabled="deleting"
                        class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl bg-red-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-red-700 disabled:opacity-60 sm:gap-2 sm:px-6 sm:py-2.5 sm:text-sm transition">
                        <i class="fas" :class="deleting ? 'fa-circle-notch fa-spin' : 'fa-trash-alt'"></i>
                        <span x-text="deleting ? 'Menghapus...' : 'Hapus'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cplDeleteModal', () => ({
            open: false,
            mode: 'single',
            items: [],
            deleteUrl: '',
            bulkUrl: '',
            subtitle: '',
            message: '',
            deleting: false,

            handleOpen(event) {
                const detail = event.detail || {};
                this.open = true;
                window.dispatchEvent(new CustomEvent('confirm-modal-opened', { bubbles: true }));
                this.mode = detail.mode || 'single';
                this.items = detail.items || [];
                this.deleteUrl = detail.deleteUrl || '';
                this.bulkUrl = detail.bulkUrl || '';
                this.deleting = false;

                if (this.mode === 'bulk') {
                    this.subtitle = `${this.items.length} CPL terpilih`;
                    this.message = `Apakah Anda yakin ingin menghapus ${this.items.length} CPL terpilih? Tindakan ini tidak dapat dibatalkan.`;
                    return;
                }

                const item = this.items[0] || {};
                this.subtitle = item.id || '';
                this.message = `Apakah Anda yakin ingin menghapus CPL ${item.id || ''}? Tindakan ini tidak dapat dibatalkan.`;
            },

            close() {
                if (this.deleting) {
                    return;
                }

                this.open = false;
                this.$nextTick(() => {
                    window.dispatchEvent(new CustomEvent('confirm-modal-closed', { bubbles: true }));
                });
            },

            csrfToken() {
                return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            },

            async parseJsonResponse(response) {
                const text = await response.text();

                if (!text) {
                    return {};
                }

                try {
                    return JSON.parse(text);
                } catch (error) {
                    console.error('Respons hapus CPL bukan JSON', text.slice(0, 200));
                    return {};
                }
            },

            async confirmDelete() {
                if (this.deleting) {
                    return;
                }

                const targetUrl = this.bulkUrl || this.deleteUrl;

                if (!targetUrl) {
                    window.dispatchEvent(new CustomEvent('cpl-delete-failed', {
                        bubbles: true,
                        detail: { message: 'URL hapus CPL tidak tersedia. Muat ulang halaman.' },
                    }));
                    return;
                }

                this.deleting = true;

                try {
                    const response = await fetch(targetUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken(),
                        },
                        body: JSON.stringify({
                            ids: this.items.map(item => item.id),
                        }),
                    });

                    const result = await this.parseJsonResponse(response);

                    if (!response.ok) {
                        window.dispatchEvent(new CustomEvent('cpl-delete-failed', {
                            bubbles: true,
                            detail: {
                                message: result.message || `Gagal menghapus CPL (HTTP ${response.status}).`,
                            },
                        }));
                        return;
                    }

                    this.open = false;
                    this.$nextTick(() => {
                        window.dispatchEvent(new CustomEvent('confirm-modal-closed', { bubbles: true }));
                    });
                    window.dispatchEvent(new CustomEvent('cpl-deleted', {
                        bubbles: true,
                        detail: {
                            message: result.message || 'CPL berhasil dihapus.',
                            deletedIds: result.deleted_ids || this.items.map(item => item.id),
                        },
                    }));
                } catch (error) {
                    console.error('Gagal menghapus CPL', error);
                    window.dispatchEvent(new CustomEvent('cpl-delete-failed', {
                        bubbles: true,
                        detail: { message: 'Terjadi kesalahan jaringan saat menghapus CPL.' },
                    }));
                } finally {
                    this.deleting = false;
                }
            },
        }));
    });
</script>