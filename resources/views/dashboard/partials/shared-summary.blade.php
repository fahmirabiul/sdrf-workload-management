<div class="mt-4">
    <div class="flex items-center space-x-2 mb-4">
        <div class="p-1.5 bg-slate-200/50 rounded-md">
            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
        </div>
        <h4 class="text-xs font-bold tracking-widest uppercase text-slate-500">Akses Cepat Modul</h4>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <a href="{{ route('requests.index') }}" class="group relative flex items-start p-6 bg-white border border-[#e2e8f0] rounded-2xl hover:border-primary/40 hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="p-4 mr-5 rounded-xl bg-primary/5 text-primary group-hover:bg-primary/10 transition-colors z-10">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="flex-1 z-10">
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-[#1e293b] group-hover:text-primary transition-colors">
                        Daftar Permintaan SDRF
                    </span>
                    <svg class="w-5 h-5 text-[#94a3b8] group-hover:text-primary transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <p class="text-sm text-[#64748b] mt-2 leading-relaxed max-w-sm">
                    Lihat status kelayakan, ajukan aplikasi baru, atau pantau proses peninjauan berkas secara real-time.
                </p>
            </div>
            <!-- Watermark Icon -->
            <svg class="absolute -bottom-6 -right-4 w-32 h-32 text-primary/[0.03] transform -rotate-12 group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </a>

        <a href="{{ route('projects.index') }}" class="group relative flex items-start p-6 bg-white border border-[#e2e8f0] rounded-2xl hover:border-slate-400 hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="p-4 mr-5 rounded-xl bg-slate-100 text-slate-600 group-hover:bg-slate-200 transition-colors z-10">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div class="flex-1 z-10">
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-[#1e293b] group-hover:text-slate-800 transition-colors">
                        Papan Pantau Proyek
                    </span>
                    <svg class="w-5 h-5 text-[#94a3b8] group-hover:text-slate-700 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <p class="text-sm text-[#64748b] mt-2 leading-relaxed max-w-sm">
                    Buka papan Kanban penugasan koding programmer, kelola timeline, dan eksekusi UAT secara efisien.
                </p>
            </div>
            <!-- Watermark Icon -->
            <svg class="absolute -bottom-6 -right-2 w-32 h-32 text-slate-800/[0.03] transform rotate-12 group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </a>
    </div>
</div>
