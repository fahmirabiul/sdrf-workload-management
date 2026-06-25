<div class="font-sans">
    <div
        class="flex flex-col lg:flex-row justify-between items-start lg:items-center border-b border-gray-100 dark:border-gray-700 pb-5 mb-6 gap-4">
        <div>
            <h4 class="text-md font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                <span>📊</span> Panel Analisis Beban Kerja Programmer
            </h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                Pemantauan kuota maksimal beban aktif 20 Story Points per programmer dalam periode terpilih.
            </p>
        </div>

        <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <select name="month" onchange="this.form.submit()"
                class="text-xs bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 dark:text-gray-300">
                @for ($m = 1; $m <= 12; $m++)
                    @php
                        $monthName = \Carbon\Carbon::create()->month($m)->translatedFormat('F');
                    @endphp
                    <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                        {{ $monthName }}
                    </option>
                @endfor
            </select>

            <select name="year" onchange="this.form.submit()"
                class="text-xs bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 dark:text-gray-300">
                @php $currentYear = now()->year; @endphp
                @for ($y = $currentYear - 2; $y <= $currentYear + 2; $y++)
                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <span
                class="text-[11px] font-mono font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300 px-3 py-2 rounded-md border border-blue-200 dark:border-blue-900 shadow-sm uppercase tracking-wider ml-0 lg:ml-2">
                📍 {{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->translatedFormat('F Y') }}
            </span>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($programmerCapacities as $prog)
            @php
                // Logika pewarnaan bar adaptif
                if ($prog['percentage'] >= 100) {
                    $colorName = 'red';
                    $statusText = '🔴 FULL CAPACITY / OVERLOAD RISK';
                } elseif ($prog['percentage'] >= 75) {
                    $colorName = 'amber';
                    $statusText = '⚠️ BEBAN KERJA TINGGI';
                } else {
                    $statusText = '🟢 TERSEDIA UNTUK TUGAS BARU';
                }
            @endphp

            <div
                class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-xl border border-gray-100 dark:border-gray-700/60 shadow-sm transition hover:shadow-md">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h5 class="font-bold text-sm text-gray-800 dark:text-gray-200">{{ $prog['name'] }}</h5>
                        <p class="text-[10px] font-medium tracking-wide mt-0.5 text-gray-400 uppercase">
                            Status: <span
                                class="{{ $prog['percentage'] >= 75 ? 'text-amber-500 font-semibold' : 'text-green-500' }}">{{ $statusText }}</span>
                        </p>
                    </div>
                    <span
                        class="text-xs font-bold font-mono px-2.5 py-0.5 rounded-full {{ $prog['percentage'] >= 100 ? 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400' : 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400' }}">
                        {{ $prog['active_points'] == (int) $prog['active_points'] ? (int) $prog['active_points'] : number_format($prog['active_points'], 1) }}
                        / {{ $prog['max_capacity'] }} Pts </span>
                </div>

                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden shadow-inner">
                    <div class="h-full rounded-full transition-all duration-500 {{ $prog['percentage'] >= 100 ? 'bg-red-500 animate-pulse' : ($prog['percentage'] >= 75 ? 'bg-amber-500' : 'bg-blue-500') }}"
                        style="width: {{ $prog['percentage'] }}%">
                    </div>
                </div>

                <div
                    class="flex justify-between items-center text-[10px] text-gray-400 dark:text-gray-500 mt-2 font-medium">
                    <span>Sisa Slot Kuota: {{ max($prog['max_capacity'] - $prog['active_points'], 0) }} Story
                        Points</span>
                    <span>{{ round($prog['percentage']) }}% Terpakai</span>
                </div>
            </div>
        @empty
            <div
                class="col-span-1 md:col-span-2 text-center py-6 bg-gray-50 dark:bg-gray-900/30 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-400 italic">Data profil kapasitas programmer belum tersedia di master data.
                </p>
            </div>
        @endforelse
    </div>
</div>
