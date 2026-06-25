<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('SDRF Multi-Role Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-medium">Selamat Datang kembali, {{ auth()->user()->username }}!</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Anda masuk dengan hak akses: <span
                        class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded font-mono text-xs uppercase">{{ auth()->user()->role }}</span>
                </p>
            </div>

            @if (auth()->user()->role === 'programmer')
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('dashboard.partials.assigned-projects-summary')
                </div>
            @endif

            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @include('dashboard.partials.shared-summary')
            </div>

            @if (auth()->user()->role === 'kepala_tik')
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('dashboard.partials.workload-analytics')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
