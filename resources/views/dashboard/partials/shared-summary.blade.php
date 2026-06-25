<div class="mt-2">
    <div class="flex items-center space-x-2 mb-4">
        <div class="p-1.5 bg-indigo-50 dark:bg-indigo-950/40 rounded-lg">
            <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
        </div>
        <h4 class="text-sm font-bold tracking-wide uppercase text-gray-700 dark:text-gray-300">Akses Cepat Modul</h4>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <a href="{{ route('requests.index') }}"
            class="group relative flex items-start p-4 bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700/70 rounded-xl shadow-sm hover:shadow-md hover:border-indigo-500 dark:hover:border-indigo-500 transition-all duration-200">
            <div
                class="p-3 mr-4 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <span
                        class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                        Daftar Permintaan SDRF
                    </span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transform group-hover:translate-x-1 transition-all"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Lihat status kelayakan, ajukan aplikasi baru, atau pantau proses peninjauan berkas.
                </p>
            </div>
        </a>

        <a href="{{ route('projects.index') }}"
            class="group relative flex items-start p-4 bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700/70 rounded-xl shadow-sm hover:shadow-md hover:border-emerald-500 dark:hover:border-emerald-500 transition-all duration-200">
            <div
                class="p-3 mr-4 rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/50 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <span
                        class="font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                        Papan Pantau Proyek
                    </span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-500 transform group-hover:translate-x-1 transition-all"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Buka papan Kanban penugasan koding programmer, kelola timeline, dan eksekusi UAT.
                </p>
            </div>
        </a>

    </div>
</div>
