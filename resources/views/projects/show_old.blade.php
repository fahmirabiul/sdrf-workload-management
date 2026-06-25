<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Lembar Kerja Proyek: {{ $project->softwareRequest->ticket_number }}
            </h2>
            <a href="{{ route('projects.index') }}"
                class="text-sm text-gray-600 dark:text-gray-400 hover:underline">&larr; Kembali ke Papan</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-md font-bold text-gray-500 uppercase tracking-wider border-b pb-2">Spesifikasi
                            Kebutuhan Sistem</h3>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-xs text-gray-400 block">Judul Fitur / Request</label>
                                <p class="text-md font-semibold">{{ $project->softwareRequest->title }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-400 block">Deskripsi Kerja</label>
                                <p class="text-sm bg-gray-50 dark:bg-gray-900 p-3 rounded mt-1 whitespace-pre-line">
                                    {{ $project->softwareRequest->description }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-400 block">Notulensi Rapat & Target Scope (Dari Kepala
                                    TIK)</label>
                                <p
                                    class="text-sm bg-blue-50 dark:bg-gray-900 p-3 rounded mt-1 border-l-4 border-blue-500 font-mono text-xs whitespace-pre-line">
                                    {{ $project->softwareRequest->meeting_notes }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-md font-bold mb-4">Status Proyek:
                            <span
                                class="uppercase text-xs font-mono font-bold px-2 py-1 rounded bg-yellow-100 text-yellow-800">
                                {{ $project->project_status }}
                            </span>
                        </h3>

                        <div class="text-xs text-gray-500 space-y-2 border-b pb-4 mb-4">
                            <p>👨‍💻 Programmer: <strong>{{ $project->programmer->user->name }}</strong></p>
                            <p>⏱️ Bobot: <strong>{{ $project->t_shirt_size }} ({{ $project->story_points }}
                                    Poin)</strong></p>
                            <p>📅 Rentang Waktu: <strong>{{ $project->start_date }} s/d
                                    {{ $project->end_date }}</strong></p>
                        </div>

                        @if (auth()->user()->role === 'programmer' && $project->programmer_id === auth()->user()->programmer->id)

                            @if ($project->project_status->value === 'waiting')
                                <form method="POST" action="{{ route('projects.update-status', $project->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_development">
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm transition shadow-md">
                                        ⚡ Mulai Ambil & Ngoding
                                    </button>
                                </form>
                            @endif

                            @if ($project->project_status->value === 'in_development')
                                <form method="POST" action="{{ route('projects.update-status', $project->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="uat_testing">
                                    <button type="submit"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm transition shadow-md">
                                        🚀 Selesai & Kirim ke UAT Staging
                                    </button>
                                </form>
                            @endif

                            @if (in_array($project->project_status->value, ['suspended', 'close_suspended']))
                                <form method="POST" action="{{ route('projects.update-status', $project->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_development">
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm transition shadow-md">
                                        ▶ Jalankan Ulang Proyek
                                    </button>
                                </form>
                            @endif

                            @if (in_array($project->project_status->value, ['uat_testing', 'ready_for_production', 'closed']))
                                <div
                                    class="p-4 bg-purple-50 dark:bg-gray-700 border-l-4 border-purple-500 rounded text-xs text-purple-700 dark:text-purple-300">
                                    🔒 <strong>Status: {{ $project->project_status->label() }}</strong><br>
                                    Kode telah di-deploy ke server staging. Menunggu feedback UAT dari User/Dosen
                                    pengaju.
                                </div>
                            @endif

                        @endif

                        @if (auth()->user()->role === 'kepala_tik' && $project->project_status->value === 'ready_for_production')
                            <div
                                class="mt-4 p-4 bg-orange-50 dark:bg-gray-700 border border-orange-200 rounded-lg shadow">
                                <p class="text-xs text-orange-700 dark:text-orange-300 mb-3 font-medium">📦 Kode sistem
                                    sudah matang & siap dirilis secara masal di lingkungan kampus.</p>

                                <form method="POST" action="{{ route('projects.close', $project->id) }}">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menutup proyek ini? Aksi ini akan membebaskan kapasitas beban kerja programmer.')"
                                        class="w-full bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold py-2 px-4 rounded text-xs transition shadow-md">
                                        🏁 Selesaikan & Tutup Proyek
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if ($project->project_status->value === 'closed')
                            <div
                                class="mt-4 p-4 bg-gray-100 dark:bg-gray-900 border-l-4 border-gray-500 rounded text-xs text-gray-700 dark:text-gray-400 font-mono">
                                🏛️ <strong>ARSIP SISTEM INFORMASI KAMPUS</strong><br>
                                Proyek ini telah selesai dikerjakan dan ditutup pada: <span
                                    class="text-blue-600 font-bold">{{ $project->closed_at }}</span>. Seluruh rekam
                                jejak terkunci demi kebutuhan akreditasi institusi.
                            </div>
                        @endif
                    </div>

                    @if ($project->project_status->value === 'uat_testing' && auth()->id() === $project->softwareRequest->user_id)
                        <div
                            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100 border-2 border-purple-400 mt-4 shadow-lg">
                            <h3 class="text-sm font-bold text-purple-600 dark:text-purple-400 mb-3">🧪 Lembar
                                Persetujuan Pengguna (UAT Form)</h3>

                            <form method="POST" action="{{ route('projects.submit-uat', $project->id) }}"
                                class="space-y-4">
                                @csrf

                                <div>
                                    <x-input-label for="uat_status" :value="__('Hasil Pengujian Aplikasi')" />
                                    <select id="uat_status" name="uat_status"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-xs font-semibold"
                                        required>
                                        <option value="">-- Pilih Hasil Uji --</option>
                                        <option value="approved" class="text-green-600 font-bold">🟢 Lolos Uji (Sesuai
                                            Kebutuhan / Siap Rilis)</option>
                                        <option value="rejected" class="text-red-600 font-bold">🔴 Butuh Revisi
                                            (Ada Bug / Belum Sesuai)</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="uat_feedback" :value="__('Catatan Pengujian & Detail Masalah')" />
                                    <textarea id="uat_feedback" name="uat_feedback" rows="4"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm font-mono text-xs"
                                        placeholder="Tuliskan modul apa saja yang sudah dites, atau tulis detail bug/revisi secara rinci jika ada..."
                                        required></textarea>
                                </div>

                                <x-primary-button
                                    class="w-full justify-center bg-purple-600 hover:bg-purple-700 active:bg-purple-800 text-xs py-2">
                                    {{ __('Kirim Komitmen UAT') }}
                                </x-primary-button>
                            </form>
                        </div>
                    @endif

                    @if ($project->uat_feedback && $project->project_status->value !== 'closed')
                        <div
                            class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 mt-4 border border-gray-200 dark:border-gray-700">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Riwayat Feedback
                                UAT Terakhir</h4>
                            <span
                                class="text-[10px] font-bold px-2 py-0.5 rounded {{ $project->uat_status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $project->uat_status === 'approved' ? 'PASSED (Lolos)' : 'REVISION NEEDED (Gagal/Revisi)' }}
                            </span>
                            <p
                                class="text-xs mt-2 text-gray-600 dark:text-gray-400 italic font-mono bg-white p-2 rounded border border-dashed">
                                "{{ $project->uat_feedback }}"
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            @if ($project->project_status->value === 'closed')
                <div
                    class="mt-6 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">

                    @if (is_null($project->softwareRequest->rating))
                        @if (auth()->id() === $project->softwareRequest->user_id)
                            <div class="text-center max-w-xl mx-auto py-2">
                                <span class="text-3xl">⭐</span>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-2">Bagaimana Kualitas
                                    Layanan Aplikasi Ini?</h3>
                                <p class="text-xs text-gray-500 mb-4">Ulasan Anda sangat berharga untuk mengevaluasi
                                    kecepatan kerja tim programmer Direktorat TIK.</p>

                                <form action="{{ route('projects.store-rating', $project->id) }}" method="POST">
                                    @csrf
                                    <div class="flex items-center justify-center space-x-2 flex-row-reverse mb-4">
                                        <input type="radio" id="star5" name="rating" value="5"
                                            class="hidden peer" required /><label for="star5"
                                            class="cursor-pointer text-3xl text-gray-300 peer-hover:text-amber-400 peer-checked:text-amber-400">★</label>
                                        <input type="radio" id="star4" name="rating" value="4"
                                            class="hidden peer" /><label for="star4"
                                            class="cursor-pointer text-3xl text-gray-300 peer-hover:text-amber-400 peer-checked:text-amber-400 ~ peer-hover:text-amber-400">★</label>
                                        <input type="radio" id="star3" name="rating" value="3"
                                            class="hidden peer" /><label for="star3"
                                            class="cursor-pointer text-3xl text-gray-300 peer-hover:text-amber-400 peer-checked:text-amber-400">★</label>
                                        <input type="radio" id="star2" name="rating" value="2"
                                            class="hidden peer" /><label for="star2"
                                            class="cursor-pointer text-3xl text-gray-300 peer-hover:text-amber-400 peer-checked:text-amber-400">★</label>
                                        <input type="radio" id="star1" name="rating" value="1"
                                            class="hidden peer" /><label for="star1"
                                            class="cursor-pointer text-3xl text-gray-300 peer-hover:text-amber-400 peer-checked:text-amber-400">★</label>
                                    </div>

                                    <textarea name="rating_feedback" rows="2"
                                        placeholder="Tuliskan ulasan singkat/pesan terima kasih untuk developer di sini (opsional)..."
                                        class="w-full text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm p-2 font-sans text-white focus:ring-amber-500 focus:border-amber-500"></textarea>

                                    <button type="submit"
                                        class="mt-3 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-md shadow-sm transition">
                                        Kirim Penilaian Kepuasan
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="text-center text-gray-400 text-xs py-2 italic">
                                ⏳ Menunggu penilaian kepuasan diisi oleh unit pengusul sistem...
                            </div>
                        @endif
                    @else
                        <div class="flex items-center space-x-4">
                            <div
                                class="bg-amber-50 dark:bg-amber-950/30 p-4 rounded-xl border border-amber-200 dark:border-amber-900 text-center flex flex-col justify-center items-center min-w-[100px]">
                                <span
                                    class="text-xs font-bold text-amber-800 dark:text-amber-400 uppercase tracking-wider">Skor
                                    Layanan</span>
                                <span
                                    class="text-3xl font-black text-amber-600 dark:text-amber-500 mt-1">{{ $project->softwareRequest->rating }}
                                    / 5</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span
                                            class="text-xl {{ $i <= $project->softwareRequest->rating ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }}">★</span>
                                    @endfor
                                    <span class="text-xs font-bold text-gray-500 ml-2">Oleh:
                                        {{ $project->softwareRequest->user->name }}</span>
                                </div>
                                <p
                                    class="text-xs italic text-gray-600 dark:text-gray-400 mt-2 bg-gray-50 dark:bg-gray-900/40 p-2.5 rounded-md border border-gray-100 dark:border-gray-700">
                                    "{!! nl2br(
                                        e($project->softwareRequest->rating_feedback ?? 'User menyukai performa aplikasi tanpa catatan masukan.'),
                                    ) !!}"
                                </p>
                            </div>
                        </div>
                    @endif

                </div>
            @endif

            <style>
                .flex-row-reverse>input:checked~label,
                .flex-row-reverse>label:hover,
                .flex-row-reverse>label:hover~label {
                    color: #fbbf24;
                }
            </style>

            @if ($project->project_status->value === 'closed')
                <div
                    class="mt-8 bg-white dark:bg-gray-800 p-8 rounded-xl shadow-md border-t-4 border-green-500 font-sans text-gray-800 dark:text-gray-200">

                    <div
                        class="border-b-2 border-gray-100 dark:border-gray-700 pb-4 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span
                                    class="p-1.5 bg-green-100 text-green-700 rounded-lg dark:bg-green-900 dark:text-green-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                        </path>
                                    </svg>
                                </span>
                                <h3
                                    class="text-xl font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">
                                    Archive Knowledge & Bundling Document</h3>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-9">Dokumen digital resmi
                                pengoperasian sistem terintegrasi Direktorat TIK.</p>
                        </div>
                        <span
                            class="bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-400 text-xs font-bold px-4 py-1.5 rounded-full border border-green-300 dark:border-green-800 tracking-wide uppercase shadow-sm">
                            ✅ ARSIP TERKUNCI (CLOSED)
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">

                        <div
                            class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <h4 class="font-bold text-xs text-gray-400 uppercase tracking-wider mb-2">📑 1. Formulir
                                SDRF</h4>
                            <div class="space-y-1 text-xs">
                                <p><strong class="text-gray-500">Tiket:</strong> <span
                                        class="font-mono font-bold text-gray-900 dark:text-gray-300">{{ $project->softwareRequest->ticket_number }}</span>
                                </p>
                                <p><strong class="text-gray-500">Judul:</strong>
                                    {{ $project->softwareRequest->title }}
                                </p>
                                <p><strong class="text-gray-500">Unit:</strong>
                                    {{ $project->softwareRequest->unit->name ?? 'N/A' }}</p>
                                <p><strong class="text-gray-500">User:</strong>
                                    {{ $project->softwareRequest->user->name }}</p>
                            </div>
                        </div>

                        <div
                            class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <h4 class="font-bold text-xs text-gray-400 uppercase tracking-wider mb-2">📝 2. Rapat Awal
                            </h4>
                            <div
                                class="text-xs italic text-gray-600 dark:text-gray-400 max-h-24 overflow-y-auto bg-white dark:bg-gray-800 p-2 rounded border border-gray-100 dark:border-gray-700">
                                "{!! nl2br(e($project->softwareRequest->meeting_notes ?? 'Tidak ada catatan khusus.')) !!}"
                            </div>
                        </div>

                        <div
                            class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <h4 class="font-bold text-xs text-gray-400 uppercase tracking-wider mb-2">🧪 3. Feedback
                                UAT</h4>
                            <div
                                class="text-xs italic text-gray-600 dark:text-gray-400 max-h-24 overflow-y-auto bg-white dark:bg-gray-800 p-2 rounded border border-gray-100 dark:border-gray-700">
                                "{!! nl2br(e($project->uat_feedback ?? 'UAT disetujui tanpa catatan revisi.')) !!}"
                            </div>
                        </div>

                        <div
                            class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <h4 class="font-bold text-xs text-gray-400 uppercase tracking-wider mb-2">⚙️ 4. Spesifikasi
                                Akhir</h4>
                            <div class="space-y-1 text-xs">
                                <p><strong class="text-gray-500">Kompleksitas:</strong> <span
                                        class="px-1.5 py-0.5 font-bold rounded bg-purple-100 text-purple-800 text-[10px]">{{ $project->t_shirt_size }}</span>
                                </p>
                                <p><strong class="text-gray-500">Total Poin:</strong> {{ $project->story_points }} Pts
                                </p>
                                <p><strong class="text-gray-500">Tutup Arsip:</strong>
                                    {{ \Carbon\Carbon::parse($project->closed_at)->translatedFormat('d F Y') }}</p>

                            </div>
                        </div>

                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <h4
                            class="font-bold text-sm text-gray-800 dark:text-gray-200 mb-4 flex items-center space-x-1">
                            <span>📜</span> <span>5. Kronologi Jejak Audit Sistem (System Audit Trail)</span>
                        </h4>

                        <div
                            class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4 border border-gray-100 dark:border-gray-700 max-h-96 overflow-y-auto shadow-inner">
                            <div
                                class="relative border-l-2 border-green-200 dark:border-green-900 ml-4 space-y-6 py-2">
                                @forelse($historyLogs as $log)
                                    <div class="relative ml-6">
                                        <span
                                            class="absolute -left-[31px] top-1 flex items-center justify-center w-4 h-4 bg-green-500 rounded-full ring-4 ring-white dark:ring-gray-800">
                                            <svg class="w-2.5 h-2.5 text-white" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </span>

                                        <div
                                            class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-100 dark:border-gray-700/60 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
                                            <div>
                                                <span
                                                    class="text-[10px] font-mono font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-md uppercase tracking-wide dark:bg-purple-950/40 dark:text-purple-400">
                                                    {{ str_replace('_', ' ', $log->action_type) }}
                                                </span>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1.5 font-medium">
                                                    {{ $log->reason }}
                                                </p>
                                            </div>
                                            <time
                                                class="text-[10px] font-mono text-gray-400 whitespace-nowrap self-end md:self-center bg-gray-50 dark:bg-gray-900 px-2 py-1 rounded">
                                                📅 {{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i:s') }}
                                            </time>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-400 italic ml-6 py-2">Tidak ada log kronologi
                                        perjalanan sistem yang terekam.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </div>
</x-app-layout>
