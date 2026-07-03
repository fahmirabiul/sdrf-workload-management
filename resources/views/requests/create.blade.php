<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Formulir Pengajuan Pengembangan Sistem (SDRF)') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft rounded-2xl border-0">
                <div class="p-6 text-slate-700">

                    <form method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="application_id" :value="__('Aplikasi Target')" class="font-bold text-xs text-slate-700" />
                                <select id="application_id" name="application_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm"
                                    required>
                                    <option value="">-- Pilih Sistem Informasi --</option>
                                    @foreach ($applications as $app)
                                        <option value="{{ $app->id }}">{{ $app->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="request_type" :value="__('Tipe Pengajuan')" class="font-bold text-xs text-slate-700" />
                                <select id="request_type" name="request_type"
                                    class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm"
                                    required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="new_feature">Fitur Baru (New Feature)</option>
                                    <option value="modification">Modifikasi Sistem (Modification)</option>
                                    <option value="bug">Perbaikan Masalah (Bug)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="title" :value="__('Judul Pengajuan')" class="font-bold text-xs text-slate-700" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm"
                                placeholder="Contoh: Integrasi payment gateway baru" required />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Detail / Spesifikasi')" class="font-bold text-xs text-slate-700" />
                            <textarea id="description" name="description" rows="4"
                                class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm"
                                placeholder="Jelaskan alur atau fungsi fitur yang Anda harapkan..." required></textarea>
                        </div>

                        <div>
                            <x-input-label for="business_impact" :value="__('Dampak Bisnis & Urgensi')" class="font-bold text-xs text-slate-700" />
                            <textarea id="business_impact" name="business_impact" rows="3"
                                class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm"
                                placeholder="Mengapa fitur ini penting? Apa dampaknya jika tidak dikembangkan segera..." required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="target_used_date" :value="__('Target Tanggal Digunakan')" class="font-bold text-xs text-slate-700" />
                                <x-text-input id="target_used_date" name="target_used_date" type="date"
                                    class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm" required />
                            </div>

                            <div>
                                <x-input-label for="attachment" :value="__('Dokumen Pendukung (PDF/Images) - Opsional')" class="font-bold text-xs text-slate-700" />
                                <input id="attachment" name="attachment" type="file"
                                    class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-slate-700 hover:file:bg-gray-200" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('requests.index') }}"
                                class="text-sm text-slate-500 hover:underline">Batal</a>
                            <x-primary-button class="bg-primary hover:bg-primary/95 shadow-soft-sm rounded-lg">
                                {{ __('Kirim Pengajuan') }}
                            </x-primary-button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
