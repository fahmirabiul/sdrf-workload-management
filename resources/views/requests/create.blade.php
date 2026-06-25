<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Formulir Pengajuan Pengembangan Sistem (SDRF)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="application_id" :value="__('Aplikasi Target')" />
                                <select id="application_id" name="application_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required>
                                    <option value="">-- Pilih Sistem Informasi --</option>
                                    @foreach ($applications as $app)
                                        <option value="{{ $app->id }}">{{ $app->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="request_type" :value="__('Tipe Pengajuan')" />
                                <select id="request_type" name="request_type"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="new_feature">Fitur Baru (New Feature)</option>
                                    <option value="modification">Modifikasi Sistem (Modification)</option>
                                    <option value="bug">Perbaikan Masalah (Bug)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="title" :value="__('Judul Pengajuan')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                placeholder="Contoh: Integrasi payment gateway baru" required />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Detail / Spesifikasi')" />
                            <textarea id="description" name="description" rows="4"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                placeholder="Jelaskan alur atau fungsi fitur yang Anda harapkan..." required></textarea>
                        </div>

                        <div>
                            <x-input-label for="business_impact" :value="__('Dampak Bisnis & Urgensi')" />
                            <textarea id="business_impact" name="business_impact" rows="3"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                placeholder="Mengapa fitur ini penting? Apa dampaknya jika tidak dikembangkan segera..." required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="target_used_date" :value="__('Target Tanggal Digunakan')" />
                                <x-text-input id="target_used_date" name="target_used_date" type="date"
                                    class="mt-1 block w-full" required />
                            </div>

                            <div>
                                <x-input-label for="attachment" :value="__('Dokumen Pendukung (PDF/Images) - Opsional')" />
                                <input id="attachment" name="attachment" type="file"
                                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-gray-200 hover:file:bg-gray-600" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('requests.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Batal</a>
                            <x-primary-button>
                                {{ __('Kirim Pengajuan') }}
                            </x-primary-button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
