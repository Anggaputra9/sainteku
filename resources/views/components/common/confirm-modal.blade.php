<template x-teleport="#modal-root">
    <div x-show="$store.confirm.open"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center p-3 sm:p-6 overflow-y-auto backdrop-blur-sm bg-gray-900/50"
        x-transition x-cloak
        @keydown.escape.window="$store.confirm.cancel()">

        <div @click.away="$store.confirm.cancel()"
            class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl dark:bg-[#0f172a] overflow-hidden">

            <div class="border-b border-gray-200 bg-white px-4 py-3 sm:px-6 sm:py-4 dark:bg-[#1e293b] dark:border-gray-700">
                <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border"
                        :class="{
                            'border-red-100 bg-red-50 dark:border-red-800/50 dark:bg-red-900/30': $store.confirm.variant === 'danger',
                            'border-purple-100 bg-purple-50 dark:border-purple-800/50 dark:bg-purple-900/30': $store.confirm.variant === 'purple',
                            'border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30': $store.confirm.variant === 'primary',
                            'border-amber-100 bg-amber-50 dark:border-amber-800/50 dark:bg-amber-900/30': $store.confirm.variant === 'warning',
                        }">
                        <i class="text-sm sm:text-base"
                            :class="{
                                'fa-solid fa-triangle-exclamation text-red-600 dark:text-red-400': $store.confirm.variant === 'danger',
                                'fa-solid fa-robot text-purple-600 dark:text-purple-400': $store.confirm.variant === 'purple',
                                'fa-solid fa-circle-info text-blue-600 dark:text-blue-400': $store.confirm.variant === 'primary',
                                'fa-solid fa-circle-exclamation text-amber-600 dark:text-amber-400': $store.confirm.variant === 'warning',
                            }"></i>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-lg" x-text="$store.confirm.title"></h3>
                        <p x-show="$store.confirm.subtitle" class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="$store.confirm.subtitle"></p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a]">
                <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line" x-text="$store.confirm.message"></p>

                <div class="flex flex-row flex-nowrap items-center justify-end gap-2 pt-2">
                    <button type="button" @click="$store.confirm.cancel()" :disabled="$store.confirm.submitting"
                        class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 focus:outline-none dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 sm:px-5 sm:py-2.5 sm:text-sm transition disabled:opacity-60">
                        <i class="fas fa-times"></i>
                        <span x-text="$store.confirm.cancelLabel"></span>
                    </button>
                    <button type="button" @click="$store.confirm.confirm()" :disabled="$store.confirm.submitting"
                        class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold text-white shadow-sm hover:opacity-90 focus:outline-none sm:gap-2 sm:px-6 sm:py-2.5 sm:text-sm transition disabled:opacity-60 disabled:cursor-not-allowed"
                        :class="{
                            'bg-red-600 hover:bg-red-700': $store.confirm.variant === 'danger',
                            'bg-purple-600 hover:bg-purple-700': $store.confirm.variant === 'purple',
                            'bg-blue-600 hover:bg-blue-700': $store.confirm.variant === 'primary',
                            'bg-amber-500 hover:bg-amber-600': $store.confirm.variant === 'warning',
                        }">
                        <i class="fas fa-check"></i>
                        <span x-text="$store.confirm.submitting ? 'Memproses…' : $store.confirm.confirmLabel"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('confirm', {
            open: false,
            title: 'Konfirmasi',
            subtitle: '',
            message: '',
            confirmLabel: 'Ya, Lanjutkan',
            cancelLabel: 'Batal',
            variant: 'danger',
            submitting: false,
            _resolver: null,

            ask(options = {}) {
                return new Promise((resolve) => {
                    this.title = options.title ?? 'Konfirmasi';
                    this.subtitle = options.subtitle ?? '';
                    this.message = options.message ?? '';
                    this.confirmLabel = options.confirmLabel ?? 'Ya, Lanjutkan';
                    this.cancelLabel = options.cancelLabel ?? 'Batal';
                    this.variant = options.variant ?? 'danger';
                    this.submitting = false;
                    this._resolver = resolve;
                    this.open = true;
                });
            },

            cancel() {
                if (this.submitting) return;
                const resolve = this._resolver;
                this._reset();
                if (resolve) resolve(false);
            },

            confirm() {
                if (this.submitting) return;
                const resolve = this._resolver;
                this._reset();
                if (resolve) resolve(true);
            },

            setSubmitting(value) {
                this.submitting = !!value;
            },

            _reset() {
                this.open = false;
                this._resolver = null;
                this.submitting = false;
            },
        });
    });
</script>