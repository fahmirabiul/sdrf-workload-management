<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Papan Pemantauan Proyek & Beban Kerja (Project Board)') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700 dark:text-gray-300">📥 Antrean Pengerjaan (Waiting)</h3>
                        <span class="bg-gray-300 text-gray-800 text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $projects->where('project_status.value', 'waiting')->count() }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        @forelse($projects->where('project_status.value', 'waiting') as $project)
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow border-l-4 border-yellow-500">
                                <div class="flex justify-between items-start mb-2">
                                    <span
                                        class="text-xs font-mono bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded font-bold">
                                        {{ $project->softwareRequest->ticket_number }}
                                    </span>
                                    <span class="text-xs font-bold text-purple-600 bg-purple-100 px-1.5 py-0.5 rounded">
                                        {{ $project->t_shirt_size }} ({{ $project->story_points }} Pts)
                                    </span>
                                </div>
                                <h4 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-2">
                                    {{ $project->softwareRequest->title }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">Unit:
                                    {{ $project->softwareRequest->unit->name ?? 'N/A' }}</p>
                                <div
                                    class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                    <span class="text-xs text-gray-400">Deadline: {{ $project->end_date }}</span>
                                    <a href="{{ route('projects.show', $project->id) }}"
                                        class="text-xs text-blue-600 hover:underline font-medium">
                                        Kelola &rarr;
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-4">Tidak ada proyek di antrean.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-blue-50 dark:bg-gray-900 p-4 rounded-lg border border-blue-100 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-blue-700 dark:text-blue-400">⚡ Sedang Dikoding (In Dev)</h3>
                        <span class="bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $projects->where('project_status.value', 'in_development')->count() }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        @forelse($projects->where('project_status.value', 'in_development') as $project)
                            <div
                                class="bg-white dark:bg-gray-800 p-4 rounded-md shadow border-l-4 {{ $project->isDelay() ? 'border-red-600 bg-red-50/50' : 'border-blue-500' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <span
                                        class="text-xs font-mono bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold">
                                        {{ $project->softwareRequest->ticket_number }}
                                    </span>

                                    <div class="flex space-x-1 items-center">
                                        @if ($project->isDelay())
                                            <span
                                                class="text-[10px] font-extrabold text-white bg-red-600 px-1.5 py-0.5 rounded uppercase animate-pulse">
                                                ⚠️ Delay
                                            </span>
                                        @endif
                                        <span
                                            class="text-xs font-bold text-purple-600 bg-purple-100 px-1.5 py-0.5 rounded">
                                            {{ $project->t_shirt_size }} ({{ $project->story_points }} Pts)
                                        </span>
                                    </div>
                                </div>

                                <h4 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-2">
                                    {{ $project->softwareRequest->title }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">Unit:
                                    {{ $project->softwareRequest->unit->name ?? 'N/A' }}</p>

                                <div
                                    class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                    <span
                                        class="text-xs {{ $project->isDelay() ? 'text-red-600 font-bold' : 'text-gray-400' }}">
                                        Deadline: {{ $project->end_date }}
                                    </span>
                                    <a href="{{ route('projects.show', $project->id) }}"
                                        class="text-xs text-blue-600 hover:underline font-medium">
                                        Kelola &rarr;
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada proyek yang mulai dikerjakan.
                            </p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700 dark:text-gray-300">⏸️ Ditangguhkan (Suspended)</h3>
                        <span class="bg-gray-300 text-gray-800 text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $projects->whereIn('project_status.value', ['suspended', 'close_suspended'])->count() }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        @foreach ($projects->where('project_status.value', 'close_suspended') as $project)
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm border border-amber-200">
                                <div class="mb-2 flex justify-between items-center">
                                    <span
                                        class="text-[10px] font-mono bg-amber-100 text-amber-800 px-2 py-0.5 rounded uppercase font-bold">
                                        {{ $project->softwareRequest->ticket_number }}
                                    </span>
                                </div>
                                <h4 class="font-semibold text-sm text-gray-900 dark:text-white mb-2">
                                    {{ $project->softwareRequest->title }}
                                </h4>
                                <p class="text-xs text-gray-500 mb-3">Programmer:
                                    {{ $project->programmer->user->name ?? 'Tanpa Nama' }}</p>

                                @if (auth()->user()->role === 'kepala_tik')
                                    <form action="{{ route('projects.resume', $project->id) }}" method="POST"
                                        class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 space-y-2">
                                        @csrf
                                        <div>
                                            <label
                                                class="block text-[10px] uppercase tracking-wide text-gray-400 font-bold">Tanggal
                                                Mulai Baru</label>
                                            <input type="date" name="start_date" required
                                                class="mt-1 block w-full text-xs rounded border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[10px] uppercase tracking-wide text-gray-400 font-bold">Tenggat
                                                Selesai Baru</label>
                                            <input type="date" name="end_date" required
                                                class="mt-1 block w-full text-xs rounded border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                        <button type="submit"
                                            class="w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-2 rounded text-xs transition duration-150">
                                            🔄 Jalankan Ulang Proyek
                                        </button>
                                    </form>
                                @else
                                    <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                        <span
                                            class="inline-flex items-center text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded">
                                            ⚠️ Ditangguhkan (Menunggu Kepala TIK)
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-green-50 dark:bg-gray-900 p-4 rounded-lg border border-green-100 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-green-700 dark:text-green-400">🎉 Tahap Rilis (UAT / Live)</h3>
                        <span class="bg-green-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $projects->whereIn('project_status.value', ['uat_testing', 'ready_for_production', 'closed'])->count() }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        @forelse($projects->whereIn('project_status.value', ['uat_testing', 'ready_for_production', 'closed']) as $project)
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow border-l-4 border-green-500">
                                <div class="flex justify-between items-start mb-2">
                                    <span
                                        class="text-xs font-mono bg-green-100 text-green-800 px-2 py-0.5 rounded font-bold">
                                        {{ $project->softwareRequest->ticket_number }}
                                    </span>
                                    <span
                                        class="text-xs capitalize font-bold px-1.5 py-0.5 rounded bg-gray-100 text-gray-700">
                                        {{ str_replace('_', ' ', $project->project_status->value) }}
                                    </span>
                                </div>
                                <h4 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-2">
                                    {{ $project->softwareRequest->title }}
                                </h4>
                                <div
                                    class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                    <span class="text-xs text-gray-400">Points: {{ $project->story_points }}</span>
                                    <a href="{{ route('projects.show', $project->id) }}"
                                        class="text-xs text-blue-600 hover:underline font-medium">
                                        Kelola &rarr;
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada proyek di tahap rilis.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
