<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'IT Workload Management') }} - Software Request & Workload Management</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            }
            h1, h2, h3, h4, h5, h6, .font-display {
                font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            }

            .grid-bg {
                background-size: 40px 40px;
                background-image: 
                    linear-gradient(to right, rgba(99, 102, 241, 0.04) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(99, 102, 241, 0.04) 1px, transparent 1px);
            }

            @keyframes pulse-subtle {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.7; transform: scale(0.95); }
            }

            .pulse-dot {
                animation: pulse-subtle 2s infinite ease-in-out;
            }

            .glow-effect {
                position: relative;
            }
            .glow-effect::before {
                content: '';
                position: absolute;
                inset: -2px;
                border-radius: inherit;
                background: linear-gradient(135deg, #6366f1, #a855f7, #3b82f6);
                z-index: -2;
                opacity: 0.12;
                filter: blur(16px);
                transition: opacity 0.3s ease;
            }
            .glow-effect::after {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: inherit;
                box-shadow: 0 0 35px rgba(99, 102, 241, 0.25);
                opacity: 0;
                transition: opacity 0.3s ease;
                z-index: -1;
            }
            .glow-effect:hover::before {
                opacity: 0.25;
            }
            .glow-effect:hover::after {
                opacity: 1;
            }
            .radial-mask {
                mask-image: radial-gradient(circle at center, black 60%, transparent 100%);
                -webkit-mask-image: radial-gradient(circle at center, black 60%, transparent 100%);
            }
        </style>
    </head>
    <body class="antialiased text-slate-800 bg-white selection:bg-indigo-500 selection:text-white">
        <!-- ===== HEADER ===== -->
        <header x-data="{ mobileOpen: false }" class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <a href="/" class="flex items-center gap-3 group">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center text-white font-bold text-sm shadow-[0_2px_10px_rgba(0,0,0,0.1)] border border-slate-700/50 group-hover:scale-105 transition-transform">
                            FR
                        </div>
                        <span class="text-lg font-bold text-slate-900 tracking-tight font-display">FLOWCAST</span>
                    </a>

                    <!-- Desktop Nav -->
                    <nav class="hidden md:flex items-center gap-8">
                        <a href="#features" class="text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                            Fitur
                        </a>
                        <a href="#workload-preview" class="text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                            Solusi
                        </a>
                        <a href="#metrics" class="text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                            Kapasitas & Aturan
                        </a>

                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors mr-4">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors mr-4">Masuk</a>
                                <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2.5 bg-slate-900 text-white text-sm font-medium rounded-full hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-900/20 transition-all duration-200">Mulai Sekarang</a>
                            @endauth
                        @endif
                    </nav>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileOpen = !mobileOpen"
                            class="md:hidden p-2 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-50 focus:outline-none"
                            aria-label="Toggle navigation menu"
                            :aria-expanded="mobileOpen">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Nav -->
                <div x-show="mobileOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="md:hidden border-t border-slate-100 py-4">
                    <div class="flex flex-col gap-3">
                        <a href="#features" @click="mobileOpen = false"
                           class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-colors">
                            Fitur Utama
                        </a>
                        <a href="#workload-preview" @click="mobileOpen = false"
                           class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-colors">
                            Preview Sistem
                        </a>
                        <a href="#metrics" @click="mobileOpen = false"
                           class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-colors">
                            Kapasitas & Aturan
                        </a>

                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" @click="mobileOpen = false"
                                   class="px-3 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 text-center shadow-sm">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" @click="mobileOpen = false"
                                   class="px-3 py-2.5 text-sm font-semibold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 text-center">
                                    Masuk Ke Sistem
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- ===== MAIN CONTENT ===== -->
        <main>
            <!-- ===== HERO SECTION ===== -->
            <section class="relative overflow-hidden grid-bg radial-mask pt-16 pb-20 lg:pt-24 lg:pb-28 border-b border-slate-100">
                <!-- Soft Glows -->
                <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-100/30 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-10 right-10 w-[300px] h-[300px] bg-amber-100/20 rounded-full blur-2xl pointer-events-none"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="lg:grid lg:grid-cols-12 lg:gap-12 lg:items-center">
                        
                        <!-- Left: Headline & Description -->
                        <div class="lg:col-span-5 flex flex-col gap-6 text-left">
                            <div>
                                <span class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100/80 rounded-full text-xs font-semibold text-indigo-700">
                                    <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full pulse-dot"></span>
                                    Internal IT Department Portal
                                </span>
                            </div>

                            <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-bold text-slate-900 tracking-tighter leading-[1.05] text-balance">
                                Optimalkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-900 via-slate-600 to-slate-900">Transparansi</span><br/> & Kapasitas Tim IT
                            </h1>

                            <p class="text-lg sm:text-xl text-slate-500 leading-relaxed max-w-2xl font-light tracking-wide mt-6">
                                Kelola siklus hidup permintaan software melalui FLOWCAST. Monitor estimasi beban kerja programmer secara proporsional dan resolusi konflik secara real-time.
                            </p>

                            <div class="flex flex-wrap gap-4 pt-2">
                                @if (Route::has('login'))
                                    @auth
                                        <a href="{{ url('/dashboard') }}"
                                           class="inline-flex items-center px-7 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.2)] hover:-translate-y-0.5 transition-all duration-200">
                                            Buka Dashboard
                                            <svg class="ml-2 w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}"
                                           class="inline-flex items-center px-7 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.2)] hover:-translate-y-0.5 transition-all duration-200">
                                            Mulai Pengajuan
                                            <svg class="ml-2 w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </a>
                                    @endauth
                                @endif
                                <a href="#features"
                                   class="inline-flex items-center px-7 py-3.5 bg-white border border-slate-200/80 text-slate-700 hover:text-slate-900 hover:bg-slate-50 hover:border-slate-300 font-medium rounded-full transition-all duration-200 shadow-sm">
                                    Pelajari Fitur
                                </a>
                            </div>
                        </div>

                        <!-- Right: Dashboard Preview Card -->
                        <div class="lg:col-span-7 mt-12 lg:mt-0">
                            <div class="animate-fade-in delay-300">
                                <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-100/80 overflow-hidden glow-effect animate-float">
                                <!-- Top Bar (Mock OS / App Window) -->
                                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-rose-400 inline-block"></span>
                                        <span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>
                                        <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span>
                                        <span class="text-xs font-semibold text-slate-400 ml-2 tracking-wide uppercase font-sans">it-workload-management v1.0</span>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-emerald-50 border border-emerald-100 rounded-full text-[10px] font-bold text-emerald-700">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full pulse-dot"></span>
                                        AKTIF
                                    </span>
                                </div>

                                <!-- Card Contents -->
                                <div class="p-6 sm:p-8 space-y-6">
                                    <!-- Dashboard Mini Header -->
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div>
                                            <h3 class="text-base font-bold text-slate-800">Ringkasan Beban Kerja IT</h3>
                                            <p class="text-xs text-slate-400 mt-0.5">Alokasi Programmer per Bulan Terhitung</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-medium px-2.5 py-1 bg-slate-100 rounded-lg text-slate-600">Juli 2026</span>
                                        </div>
                                    </div>

                                    <!-- Grid Stats -->
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="bg-indigo-50/40 border border-indigo-100/60 rounded-xl p-4">
                                            <p class="text-xs font-medium text-indigo-600">Tiket Baru</p>
                                            <p class="text-2xl font-bold text-indigo-900 mt-1 font-sans">04</p>
                                        </div>
                                        <div class="bg-amber-50/40 border border-amber-100/60 rounded-xl p-4">
                                            <p class="text-xs font-medium text-amber-600">Development</p>
                                            <p class="text-2xl font-bold text-amber-900 mt-1 font-sans">07</p>
                                        </div>
                                        <div class="bg-emerald-50/40 border border-emerald-100/60 rounded-xl p-4">
                                            <p class="text-xs font-medium text-emerald-600">Selesai UAT</p>
                                            <p class="text-2xl font-bold text-emerald-900 mt-1 font-sans">15</p>
                                        </div>
                                    </div>

                                    <!-- Programmer allocation bars -->
                                    <div class="space-y-4 pt-2">
                                        <!-- Programmer 1 -->
                                        <div class="space-y-1.5">
                                            <div class="flex justify-between text-xs font-semibold">
                                                <span class="text-slate-700">Rizky R. (Senior Developer)</span>
                                                <span class="text-indigo-600">18 / 20 Story Points (90%)</span>
                                            </div>
                                            <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full" style="width: 90%;"></div>
                                            </div>
                                        </div>

                                        <!-- Programmer 2 -->
                                        <div class="space-y-1.5">
                                            <div class="flex justify-between text-xs font-semibold">
                                                <span class="text-slate-700">Fajar B. (Frontend Dev)</span>
                                                <span class="text-emerald-600">12 / 20 Story Points (60%)</span>
                                            </div>
                                            <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full" style="width: 60%;"></div>
                                            </div>
                                        </div>

                                        <!-- Programmer 3 -->
                                        <div class="space-y-1.5">
                                            <div class="flex justify-between text-xs font-semibold">
                                                <span class="text-slate-700">Mega P. (Backend Dev)</span>
                                                <span class="text-rose-500 font-bold">22 / 20 Story Points (Overloaded)</span>
                                            </div>
                                            <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-rose-400 to-rose-500 rounded-full" style="width: 100%;"></div>
                                            </div>
                                            <span class="block text-[10px] text-rose-500/90 font-medium">⚠️ Melebihi kapasitas maksimal! Perlu aktivasi Protokol Resolusi Konflik.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
            </section>

            <!-- ===== FEATURES SECTION ===== -->
            <section id="features" class="py-20 lg:py-24 bg-slate-50/50 border-b border-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest bg-indigo-50 border border-indigo-100/50 px-3 py-1 rounded-full">Fitur Utama</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-4 tracking-tight">
                            Solusi Kolaborasi Siklus Hidup Software
                        </h2>
                        <p class="mt-4 text-slate-500 text-lg leading-relaxed">
                            Dirancang khusus untuk kebutuhan internal IT department kampus guna menghindari kelebihan muatan tugas programmer.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all duration-200 flex flex-col gap-5">
                            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Manajemen Pengajuan</h3>
                                <p class="mt-2 text-slate-500 text-sm leading-relaxed">
                                    User dari unit lain dapat membuat permintaan pengembangan software. Seluruh tahapan dari analisis, UAT, hingga status produksi tercatat rapi.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all duration-200 flex flex-col gap-5">
                            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Kapasitas Kerja & Poin</h3>
                                <p class="mt-2 text-slate-500 text-sm leading-relaxed">
                                    Menggunakan standarisasi T-Shirt size (S, M, L, XL) yang otomatis dikonversi ke Story Points secara proporsional berdasarkan rentang tanggal kerja.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all duration-200 flex flex-col gap-5">
                            <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Protokol Konflik Otomatis</h3>
                                <p class="mt-2 text-slate-500 text-sm leading-relaxed">
                                    Ketika proyek darurat disetujui dan melampaui batas kapasitas programmer (20 Pts/bulan), sistem otomatis membagi proyek aktif ke status Close-Suspended.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ===== DETAILS & WORKLOAD PREVIEW ===== -->
            <section id="workload-preview" class="py-20 lg:py-24 bg-white border-b border-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
                        <div>
                            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Kalkulasi Story Points</span>
                            <h2 class="text-3xl font-extrabold text-slate-900 mt-4 tracking-tight leading-tight">
                                Kapasitas Proporsional Bulanan yang Akurat
                            </h2>
                            <p class="mt-4 text-slate-500 leading-relaxed text-base">
                                Penghitungan kapasitas tidak lagi dipukul rata secara manual. Sistem FLOWCAST menghitung bobot poin proyek secara harian berdasarkan irisan tanggal aktif dalam bulan bersangkutan.
                            </p>

                            <!-- Mini Table -->
                            <div class="mt-8 border border-slate-150 rounded-xl overflow-hidden shadow-sm">
                                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                    <thead>
                                        <tr class="bg-slate-50 border-b border-slate-150 text-slate-600 font-semibold">
                                            <th class="p-3.5">Ukuran Proyek</th>
                                            <th class="p-3.5">Rentang Hari Kerja</th>
                                            <th class="p-3.5 text-right">Story Points</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-slate-700">
                                        <tr>
                                            <td class="p-3.5 font-medium"><span class="px-2 py-0.5 bg-slate-100 rounded text-[11px] font-bold text-slate-600">S (Small)</span></td>
                                            <td class="p-3.5">1 – 3 Hari</td>
                                            <td class="p-3.5 text-right font-bold text-slate-900">2 Pts</td>
                                        </tr>
                                        <tr>
                                            <td class="p-3.5 font-medium"><span class="px-2 py-0.5 bg-slate-100 rounded text-[11px] font-bold text-slate-600">M (Medium)</span></td>
                                            <td class="p-3.5">7 – 14 Hari</td>
                                            <td class="p-3.5 text-right font-bold text-slate-900">5 Pts</td>
                                        </tr>
                                        <tr>
                                            <td class="p-3.5 font-medium"><span class="px-2 py-0.5 bg-slate-100 rounded text-[11px] font-bold text-slate-600">L (Large)</span></td>
                                            <td class="p-3.5">21 – 28 Hari</td>
                                            <td class="p-3.5 text-right font-bold text-slate-900">10 Pts</td>
                                        </tr>
                                        <tr>
                                            <td class="p-3.5 font-medium"><span class="px-2 py-0.5 bg-slate-100 rounded text-[11px] font-bold text-slate-600">XL (Extra Large)</span></td>
                                            <td class="p-3.5">28+ Hari</td>
                                            <td class="p-3.5 text-right font-bold text-slate-900">20 Pts</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Right: Visual Cards of Logs / Audit Trail -->
                        <div class="mt-12 lg:mt-0 space-y-4">
                            <div class="bg-slate-950 rounded-[2rem] p-8 border border-slate-800 shadow-2xl shadow-slate-900/50">
                                <h4 class="font-display font-semibold text-white tracking-wide mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Audit Trail & Log Riwayat Proyek
                                </h4>

                                <div class="space-y-3">
                                    <!-- Log Item 1 -->
                                    <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-800/80 text-sm flex flex-col gap-1.5 backdrop-blur-sm hover:bg-slate-800 transition-colors">
                                        <div class="flex justify-between">
                                            <span class="text-slate-200 font-medium">Status Diubah -> DEVELOPMENT</span>
                                            <span class="text-slate-500 text-xs">Baru Saja</span>
                                        </div>
                                        <p class="text-slate-400 leading-relaxed text-xs">Tiket REQ-2026-002 dialokasikan ke Fajar B. oleh Kepala UPT TIK.</p>
                                    </div>

                                    <!-- Log Item 2 -->
                                    <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-800/80 text-sm flex flex-col gap-1.5 backdrop-blur-sm hover:bg-slate-800 transition-colors">
                                        <div class="flex justify-between">
                                            <span class="text-amber-400 font-medium">⚠️ Konflik Penjadwalan Terdeteksi</span>
                                            <span class="text-slate-500 text-xs">10 Menit Lalu</span>
                                        </div>
                                        <p class="text-slate-400 leading-relaxed text-xs">Aktivasi proyek mendesak REQ-2026-004 memicu suspensi proyek REQ-2026-001 (Sisa 5 Pts dikloning ke antrian Waiting).</p>
                                    </div>

                                    <!-- Log Item 3 -->
                                    <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-800/80 text-sm flex flex-col gap-1.5 backdrop-blur-sm hover:bg-slate-800 transition-colors">
                                        <div class="flex justify-between">
                                            <span class="text-slate-200 font-medium">UAT Diterima & Ditandatangani</span>
                                            <span class="text-slate-500 text-xs">1 Jam Lalu</span>
                                        </div>
                                        <p class="text-slate-400 leading-relaxed text-xs">Pemohon (Unit Keuangan) menyetujui hasil review UAT dengan rating 5 bintang.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ===== METRICS & ATURAN ===== -->
            <section id="metrics" class="py-16 bg-slate-50/50 border-b border-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-4 w-full">
                        <div class="flex flex-col items-center justify-center p-6 text-center">
                            <p class="font-display text-5xl lg:text-6xl font-bold text-slate-900 tracking-tighter">20<span class="text-3xl text-slate-400">Pts</span></p>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mt-4">Batas Beban Kerja</p>
                        </div>
                        <div class="flex flex-col items-center justify-center p-6 text-center">
                            <p class="font-display text-5xl lg:text-6xl font-bold text-slate-900 tracking-tighter">100<span class="text-3xl text-slate-400">%</span></p>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mt-4">Audit Terlacak</p>
                        </div>
                        <div class="flex flex-col items-center justify-center p-6 text-center">
                            <p class="font-display text-5xl lg:text-6xl font-bold text-slate-900 tracking-tighter">3</p>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mt-4">Hak Akses Sistem</p>
                        </div>
                        <div class="flex flex-col items-center justify-center p-6 text-center">
                            <p class="font-display text-5xl lg:text-6xl font-bold text-slate-900 tracking-tighter">24<span class="text-3xl text-slate-400">/7</span></p>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mt-4">Akses Terbuka</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ===== CTA SECTION ===== -->
            <section class="py-20 bg-white">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <div class="bg-slate-950 rounded-[3rem] sm:rounded-[4rem] p-12 sm:p-24 relative overflow-hidden shadow-2xl max-w-5xl mx-auto flex flex-col items-center text-center">
                        <!-- Ornamen background halus -->
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-800/40 via-transparent to-transparent pointer-events-none"></div>

                        <h2 class="font-display text-4xl sm:text-5xl font-bold text-white tracking-tighter relative z-10">
                            Mulai Kelola Workload IT Anda
                        </h2>
                        <p class="mt-6 text-slate-400 text-lg max-w-xl mx-auto leading-relaxed relative z-10">
                            Gunakan kredensial akun IT Anda untuk mengelola penugasan, menyetujui tiket, atau mengirim ulasan UAT.
                        </p>
                        <div class="mt-8 flex justify-center gap-4 relative z-10">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-8 py-4 bg-white text-slate-900 hover:bg-slate-100 font-semibold rounded-full shadow-[0_0_40px_rgba(255,255,255,0.1)] hover:scale-105 transition-all duration-300">
                                        Buka Dashboard <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 bg-white text-slate-900 hover:bg-slate-100 font-semibold rounded-full shadow-[0_0_40px_rgba(255,255,255,0.1)] hover:scale-105 transition-all duration-300">
                                        Masuk Sekarang <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- ===== FOOTER ===== -->
        <footer class="bg-slate-900 border-t border-slate-800 text-slate-400 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16 pt-4">
                    <!-- Kolom 1: Brand -->
                    <div class="md:col-span-2">
                        <a href="/" class="flex items-center gap-3 mb-6 group">
                            <div class="w-8 h-8 rounded-xl bg-slate-800 flex items-center justify-center text-white font-bold text-sm border border-slate-700 group-hover:bg-slate-700 transition-colors">
                                R
                            </div>
                            <span class="font-display text-xl font-bold text-white tracking-tight">FLOWCAST</span>
                        </a>
                        <p class="text-sm text-slate-400 leading-relaxed max-w-md font-light">
                            Flowcast adalah platform untuk pengelolaan alokasi SDM dan transparansi pengerjaan software secara presisi.
                        </p>
                    </div>
                    <!-- Kolom 2: Navigasi -->
                    <div>
                        <h3 class="text-slate-100 font-medium mb-6 text-sm tracking-wide">Navigasi</h3>
                        <ul class="space-y-4 text-sm text-slate-400">
                            <li><a href="#" class="hover:text-white transition-colors">Beranda</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Panduan Sistem</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Hubungi Kami</a></li>
                        </ul>
                    </div>
                    <!-- Kolom 3: Tautan Terkait -->
                    <div>
                        <h3 class="text-slate-100 font-medium mb-6 text-sm tracking-wide">Tautan Terkait</h3>
                        <ul class="space-y-4 text-sm text-slate-400">
                            <li><a href="#" class="hover:text-white transition-colors">Helpdesk IT</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Bottom Bar -->
                <div class="border-t border-slate-800/60 pt-8 pb-12 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500">
                        &copy; {{ date('Y') }} IT Department. Hak cipta dilindungi.
                    </p>
                    <div class="flex items-center gap-8 text-xs text-slate-500">
                        <a href="#" class="hover:text-slate-300 transition-colors">Syarat & Ketentuan</a>
                        <a href="#" class="hover:text-slate-300 transition-colors">Kebijakan Privasi</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
