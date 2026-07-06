<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#1e293b] leading-tight">
            {{ __('SDRF Multi-Role Dashboard') }}
        </h2>
    </x-slot>

    <div class="space-y-6">

        <div class="p-6 bg-white shadow-soft rounded-2xl text-[#1e293b] border-0">
            <h3 class="text-lg font-bold text-slate-700">Selamat Datang kembali, {{ auth()->user()->username }}!</h3>
            <p class="text-sm text-slate-500 mt-1">
                Anda masuk dengan hak akses: <span
                    class="px-2.5 py-1 bg-primary/10 text-primary rounded-1.8 font-semibold text-xs uppercase">{{ auth()->user()->role }}</span>
            </p>
        </div>

        @if (auth()->user()->role === 'programmer')
            <div class="p-6 bg-white shadow-soft rounded-2xl border-0">
                @include('dashboard.partials.assigned-projects-summary')
            </div>
        @endif

        <div class="p-6 bg-white shadow-soft rounded-2xl border-0">
            @include('dashboard.partials.shared-summary')
        </div>

        @if (auth()->user()->role === 'kepala_tik')
            <div class="p-6 bg-white shadow-soft rounded-2xl border-0">
                @include('dashboard.partials.workload-analytics')
            </div>
        @endif
    </div>
</x-app-layout>
