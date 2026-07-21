<x-app-layout>
    <div class="space-y-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-[#1e293b] tracking-tight">Dashboard IT Workload Management</h2>
                <p class="text-slate-500 mt-2 text-base">Selamat Datang kembali, <span class="font-semibold text-primary">{{ auth()->user()->username }}!</span></p>
            </div>
            
            <div class="flex items-center gap-2 bg-primary/10 px-4 py-2.5 rounded-xl text-primary font-bold text-sm border border-primary/20 shadow-sm mt-1 md:mt-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                AKSES: {{ strtoupper(auth()->user()->role) }}
            </div>
        </div>

        @if (auth()->user()->role === 'programmer')
            <div>
                @include('dashboard.partials.assigned-projects-summary')
            </div>
        @endif

        <div>
            @include('dashboard.partials.shared-summary')
        </div>

        @if (auth()->user()->role === 'kepala_tik')
            <div>
                @include('dashboard.partials.workload-analytics')
            </div>
        @endif
    </div>
</x-app-layout>
