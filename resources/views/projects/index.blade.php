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
                    <div class="space-y-4 overflow-y-auto max-h-[600px]">
                        @forelse ($projects->where('project_status.value', 'waiting') as $project)
                            <div
                                class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm border-l-4 border-yellow-500">
                                <div class="flex justify-between items-start mb-2">
                                    <span
                                        class="text-[10px] font-mono text-gray-400">#{{ $project->softwareRequest->ticket_number }}</span>
                                    <span
                                        class="text-[10px] font-bold px-1.5 py-0.5 bg-yellow-100 text-yellow-800 rounded">Size:
                                        {{ $project->t_shirt_size }}</span>
                                </div>
                                <h4 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-2">
                                    {{ $project->phase_title ?? $project->softwareRequest->title }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">Dev:
                                    {{ $project->programmer->user->name ?? 'Unassigned' }}</p>

                                @if ($project->start_date && $project->end_date)
                                    <p class="text-[10px] text-gray-400 mt-2">📅
                                        {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/y') }} -
                                        {{ \Carbon\Carbon::parse($project->end_date)->format('d/m/y') }}</p>
                                @else
                                    <span
                                        class="inline-block mt-2 text-[10px] text-red-600 dark:text-red-400 font-bold bg-red-50 dark:bg-red-950/30 px-2 py-0.5 rounded animate-pulse">⚠️
                                        Butuh Alokasi Jadwal</span>
                                @endif

                                <div
                                    class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                    <span class="text-xs text-gray-400">Points: {{ $project->story_points }} Pts</span>
                                    <a href="{{ route('projects.show', $project->id) }}"
                                        class="text-xs text-blue-600 hover:underline font-medium">Kelola &rarr;</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada proyek dalam antrean.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700 dark:text-gray-300">💻 Sedang Dikoding (In Dev)</h3>
                        <span class="bg-blue-200 text-blue-800 text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $projects->where('project_status.value', 'in_development')->count() }}
                        </span>
                    </div>
                    <div class="space-y-4 overflow-y-auto max-h-[600px]">
                        @forelse ($projects->where('project_status.value', 'in_development') as $project)
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm border-l-4 border-blue-500">
                                <div class="flex justify-between items-start mb-2">
                                    <span
                                        class="text-[10px] font-mono text-gray-400">#{{ $project->softwareRequest->ticket_number }}</span>
                                    @if ($project->isDelay())
                                        <span
                                            class="text-[9px] bg-red-100 text-red-800 px-1.5 py-0.5 font-extrabold rounded animate-pulse">DELAY</span>
                                    @endif
                                </div>
                                <h4 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-2">
                                    {{ $project->phase_title ?? $project->softwareRequest->title }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">Dev: {{ $project->programmer->user->name }}</p>
                                <div
                                    class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                    <span class="text-xs text-gray-400">Points: {{ $project->story_points }}</span>
                                    <a href="{{ route('projects.show', $project->id) }}"
                                        class="text-xs text-blue-600 hover:underline font-medium">Kelola &rarr;</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-4">Tidak ada proyek yang sedang dikoding.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700 dark:text-gray-300">🧪 Tahap Pengujian (UAT)</h3>
                        <span class="bg-purple-200 text-purple-800 text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $projects->where('project_status.value', 'uat_testing')->count() }}
                        </span>
                    </div>
                    <div class="space-y-4 overflow-y-auto max-h-[600px]">
                        @forelse ($projects->where('project_status.value', 'uat_testing') as $project)
                            <div
                                class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm border-l-4 border-purple-500">
                                <div class="flex justify-between items-start mb-2">
                                    <span
                                        class="text-[10px] font-mono text-gray-400">#{{ $project->softwareRequest->ticket_number }}</span>
                                </div>
                                <h4 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-2">
                                    {{ $project->softwareRequest->title }}
                                </h4>
                                <div
                                    class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                    <span class="text-xs text-gray-400">Points: {{ $project->story_points }}</span>
                                    <a href="{{ route('projects.show', $project->id) }}"
                                        class="text-xs text-blue-600 hover:underline font-medium">Kelola &rarr;</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada proyek di tahap UAT.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700 dark:text-gray-300">🚀 Rilis & Arsip</h3>
                        <span class="bg-green-200 text-green-800 text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $projects->whereIn('project_status.value', ['ready_for_production', 'production', 'closed'])->count() }}
                        </span>
                    </div>
                    <div class="space-y-4 overflow-y-auto max-h-[600px]">
                        @forelse ($projects->whereIn('project_status.value', ['ready_for_production', 'production', 'closed']) as $project)
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm border-l-4 border-green-500">
                                <div class="flex justify-between items-start mb-2">
                                    <span
                                        class="text-[10px] font-mono text-gray-400">#{{ $project->softwareRequest->ticket_number }}</span>
                                    <span
                                        class="text-[10px] capitalize font-bold px-1.5 py-0.5 rounded bg-gray-100 text-gray-700">
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
                                        class="text-xs text-blue-600 hover:underline font-medium">Kelola &rarr;</a>
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
