<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                Lembar Kerja Proyek: {{ $project->softwareRequest->ticket_number }}
            </h2>
            <a href="{{ route('projects.index') }}"
                class="text-sm text-slate-500 hover:underline">&larr; Kembali ke Papan</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white shadow sm:rounded-lg p-6 text-gray-900">
                        <h3 class="text-md font-bold text-gray-500 uppercase tracking-wider border-b pb-2">Spesifikasi
                            Kebutuhan Sistem</h3>

                        <div class="mt-4 space-y-3">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Judul Komponen / Proyek</label>
                                <p class="text-base font-semibold text-slate-800">
                                    {{ $project->phase_title ?? $project->softwareRequest->title }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Deskripsi Masalah Kerja</label>
                                <p
                                    class="text-sm text-slate-500 bg-gray-50 p-3 rounded mt-1 whitespace-pre-line">
                                    {{ $project->softwareRequest->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white shadow sm:rounded-lg p-6 text-gray-900">
                        <h3 class="text-md font-bold text-gray-500 uppercase tracking-wider border-b pb-2">Status &
                            Manajemen Kerja</h3>

                        <div class="mt-4 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Status Saat Ini:</span>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold {{ $project->project_status->colorClass() }}">
                                    {{ $project->project_status->label() }}
                                </span>
                            </div>

                            <div class="border-t pt-4 border-gray-100 space-y-2">
                                <p class="text-xs text-gray-400"><strong>Programmer:</strong>
                                    {{ $project->programmer->user->name }}</p>
                                <p class="text-xs text-gray-400"><strong>T-Shirt Size / Beban:</strong>
                                    {{ $project->t_shirt_size }} ({{ $project->story_points }} Story Points)</p>
                                <p class="text-xs text-gray-400">
                                    <strong>Jadwal Kerja:</strong>
                                    @if ($project->start_date && $project->end_date)
                                        {{ \Carbon\Carbon::parse($project->start_date)->format('d M Y') }} s/d
                                        {{ \Carbon\Carbon::parse($project->end_date)->format('d M Y') }}
                                    @else
                                        <span class="text-red-500 font-bold">Belum Dijadwalkan (Mengambang)</span>
                                    @endif
                                </p>
                            </div>

                            @if ($project->project_status->value === 'waiting' && !$project->start_date && auth()->user()->role === 'kepala_tik')
                                <div
                                    class="mt-4 p-4 bg-yellow-50 border border-yellow-300 rounded-md">
                                    <h4 class="text-xs font-bold text-yellow-800 uppercase mb-2">📌
                                        Alokasikan Jadwal Baru</h4>
                                    <p class="text-[11px] text-yellow-700 mb-3">Proyek ini
                                        merupakan sisa pekerjaan dari suspend darurat. Berikan alokasi tanggal baru
                                        sesuai ketentuan waktu T-Shirt Size ({{ $project->t_shirt_size }}).</p>

                                    <form action="{{ route('projects.update-status', $project->id) }}" method="POST"
                                        class="space-y-3">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <label class="block text-[11px] text-gray-500">Tanggal Mulai</label>
                                            <input type="date" name="start_date"
                                                class="mt-1 block w-full text-xs border-gray-300 rounded-md shadow-sm focus:ring-blue-500"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] text-gray-500">Tanggal Selesai</label>
                                            <input type="date" name="end_date"
                                                class="mt-1 block w-full text-xs border-gray-300 rounded-md shadow-sm focus:ring-blue-500"
                                                required>
                                        </div>
                                        <button type="submit"
                                            class="w-full text-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded shadow-sm transition">
                                            Sah & Aktifkan Proyek
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @if ($project->project_status->value === 'waiting' && $project->start_date && auth()->user()->role === 'programmer')
                                <form action="{{ route('projects.update-status', $project->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_development">
                                    <button type="submit"
                                        class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded uppercase tracking-wider shadow transition">
                                        🚀 Mulai Kerjakan (Start Coding)
                                    </button>
                                </form>
                            @endif

                            @if ($project->project_status->value === 'in_development' && auth()->user()->role === 'programmer')
                                <form action="{{ route('projects.update-status', $project->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="uat_testing">
                                    <button type="submit"
                                        class="w-full px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded uppercase tracking-wider shadow transition">
                                        🧪 Ajukan ke Tahap UAT Testing
                                    </button>
                                </form>
                            @endif

                            @if ($project->project_status->value === 'uat_testing' && auth()->id() === $project->softwareRequest->user_id)
                                <div class="bg-purple-50 p-4 rounded-md border border-purple-200">
                                    <h4 class="text-xs font-bold text-purple-800 mb-2">Form
                                        Kelayakan Aplikasi (UAT)</h4>
                                    <form action="{{ route('projects.submit-uat', $project->id) }}" method="POST"
                                        class="space-y-3">
                                        @csrf
                                        <select name="uat_status"
                                            class="w-full text-xs rounded border-gray-300">
                                            <option value="approved">Setuju, Aplikasi Layak Rilis</option>
                                            <option value="rejected">Tolak, Masih Ada Bug / Revisi</option>
                                        </select>
                                        <textarea name="uat_feedback" rows="3" placeholder="Tulis catatan revisi atau apresiasi..."
                                            class="w-full text-xs rounded border-gray-300"></textarea>
                                        <button type="submit"
                                            class="w-full py-2 bg-purple-600 text-white text-xs font-bold rounded">Kirim
                                            Keputusan UAT</button>
                                    </form>
                                </div>
                            @endif

                            @if (
                                $project->project_status->value === 'ready_for_production' &&
                                    in_array(auth()->user()->role, ['kepala_tik', 'infra']))
                                <form action="{{ route('projects.close', $project->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold text-xs rounded uppercase tracking-wider shadow transition">
                                        ⚙️ Eksekusi Deployment & Tutup Proyek
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6 text-gray-900">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider border-b pb-2">Log Kronologi &
                    Histori Audit Proyek</h3>
                <div class="mt-4 space-y-4">
                    @forelse ($historyLogs as $log)
                        <div
                            class="p-3 bg-gray-50/40 rounded border border-gray-100 flex justify-between items-start text-xs">
                            <div>
                                <span
                                    class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded font-mono font-bold">{{ str_replace('_', ' ', $log->action_type) }}</span>
                                <p class="text-slate-500 mt-1">{{ $log->reason }}</p>
                            </div>
                            <span
                                class="text-[10px] text-gray-400 font-mono">{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i') }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic">Belum ada log kronologi perjalanan sistem.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
