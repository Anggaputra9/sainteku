<div x-show="openCreate || openEdit"
    class="fixed inset-0 z-[999990] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40"
    x-transition x-cloak>
    <div @click.away="openCreate=false; openEdit=false"
        class="relative w-full max-w-3xl rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 flex flex-col max-h-[95vh] overflow-hidden">

        {{-- Header --}}
        <div class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
            <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-brain text-indigo-500"></i>
                <span x-text="openEdit ? 'Edit Konfigurasi AI' : 'Tambah Konfigurasi AI'"></span>
            </h3>
            <button type="button" @click="openCreate=false; openEdit=false" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form :method="'POST'" :action="formAction" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <template x-if="openEdit"><input type="hidden" name="_method" value="PUT"></template>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a]">

                {{-- Provider & Nama --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Nama Konfigurasi *</label>
                        <input name="name" x-model="form.name" required maxlength="100"
                            placeholder="Contoh: OpenAI GPT-4o"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Provider *</label>
                        <select name="provider" x-model="form.provider" @change="onProviderChange()" required
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            <option value="">-- Pilih Provider --</option>
                            @foreach($providers as $key => $info)
                                <option value="{{ $key }}">{{ $info['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Model & API Key --}}
                <div class="rounded-xl border border-indigo-200 bg-indigo-50/40 p-4 dark:border-indigo-900/40 dark:bg-indigo-900/10">
                    <h4 class="text-sm font-bold text-indigo-700 dark:text-indigo-300 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-key"></i> Konfigurasi API
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Model *</label>
                            {{-- Show select for providers with preset models --}}
                            <template x-if="Object.keys(currentModels).length > 0">
                                <div class="space-y-2">
                                    <select x-model="form.model" required
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                        <option value="">-- Pilih Model --</option>
                                        <template x-for="(label, key) in currentModels" :key="key">
                                            <option :value="key" x-text="label"></option>
                                        </template>
                                        <option value="__custom__">Custom / Input Manual</option>
                                    </select>
                                    {{-- Show text input if custom selected --}}
                                    <input x-show="form.model === '__custom__'" type="text" name="model"
                                        placeholder="Masukkan nama model custom"
                                        @input="form.model = $event.target.value"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                    <input x-show="form.model !== '__custom__'" type="hidden" name="model" :value="form.model">
                                </div>
                            </template>
                            {{-- Show text input for providers without preset models --}}
                            <template x-if="Object.keys(currentModels).length === 0">
                                <input type="text" name="model" x-model="form.model" required
                                    placeholder="Contoh: gpt-4, claude-3, custom-model"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </template>
                            <p class="mt-1 text-[10px] text-gray-500">
                                <span x-show="Object.keys(currentModels).length > 0">Pilih dari preset atau input manual</span>
                                <span x-show="Object.keys(currentModels).length === 0">Masukkan nama model sesuai dokumentasi API</span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">API Endpoint</label>
                            <input name="api_endpoint" x-model="form.api_endpoint" maxlength="255"
                                placeholder="https://api.openai.com/v1"
                                class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">API Key
                                <span x-show="!openEdit" class="text-red-500">*</span>
                            </label>
                            <input type="password" name="api_key" x-model="form.api_key"
                                :placeholder="openEdit ? '•••• (kosongkan jika tidak diubah)' : 'API Key dari provider'"
                                class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            <p class="mt-1 text-[10px] text-gray-500" x-show="currentKeyHint" x-text="currentKeyHint"></p>
                        </div>
                    </div>
                </div>

                {{-- Model Parameters --}}
                <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-gray-800/40 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-indigo-500"></i> Parameter Model
                        </h4>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Temperature (0-2)</label>
                            <input type="number" step="0.1" min="0" max="2" name="temperature" x-model="form.temperature"
                                class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <p class="mt-1 text-[10px] text-gray-500">Kreativitas respons (default: 0.7)</p>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Max Tokens</label>
                            <input type="number" min="1" max="100000" name="max_tokens" x-model="form.max_tokens"
                                class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <p class="mt-1 text-[10px] text-gray-500">Panjang maksimal respons (default: 2000)</p>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Top P (0-1)</label>
                            <input type="number" step="0.1" min="0" max="1" name="top_p" x-model="form.top_p"
                                class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Daily Limit</label>
                            <input type="number" min="0" name="daily_limit" x-model="form.daily_limit"
                                placeholder="0 = unlimited"
                                class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        </div>
                    </div>
                </div>

                {{-- Status & Priority --}}
                <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-700 dark:bg-[#1e293b]">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        <li class="px-4 py-3 grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_220px] gap-3 md:gap-6 items-center">
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-700 dark:text-gray-300">Prioritas</div>
                                <div class="text-xs text-gray-500 mt-0.5">Angka kecil = dipakai duluan</div>
                            </div>
                            <div class="md:justify-self-end w-full md:w-[220px]">
                                <input type="number" min="0" name="priority" x-model="form.priority" placeholder="0"
                                    class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            </div>
                        </li>
                        <li class="px-4 py-3 grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_220px] gap-3 md:gap-6 items-center">
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-700 dark:text-gray-300">Status Aktif</div>
                                <div class="text-xs text-gray-500 mt-0.5">Aktifkan untuk digunakan</div>
                            </div>
                            <div class="md:justify-self-end w-full md:w-[220px]">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" x-model="form.is_active" value="1" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </li>
                        <li class="px-4 py-3 grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_220px] gap-3 md:gap-6 items-center">
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-700 dark:text-gray-300">Set sebagai Default</div>
                                <div class="text-xs text-gray-500 mt-0.5">Digunakan untuk koreksi ujian</div>
                            </div>
                            <div class="md:justify-self-end w-full md:w-[220px]">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_default" x-model="form.is_default" value="1" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-amber-500"></div>
                                </label>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Catatan</label>
                    <textarea name="notes" x-model="form.notes" rows="3" maxlength="1000"
                        placeholder="Catatan tambahan..."
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white"></textarea>
                </div>

            </div>

            {{-- Footer --}}
            <div class="shrink-0 flex items-center justify-end gap-3 border-t border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                <button type="button" @click="openCreate=false; openEdit=false"
                    class="rounded-lg bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">
                    Batal
                </button>
                <button type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">
                    <span x-text="openEdit ? 'Update' : 'Simpan'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
