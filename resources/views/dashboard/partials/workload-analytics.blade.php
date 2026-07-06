<div class="font-sans">
    <div
        class="flex flex-col lg:flex-row justify-between items-start lg:items-center border-b border-[#e2e8f0] pb-5 mb-6 gap-4">
        <div>
            <h4 class="text-md font-bold text-[#1e293b] flex items-center gap-2">
                <span>📊</span> Panel Analisis Beban Kerja Programmer
            </h4>
            <p class="text-xs text-[#64748b] mt-0.5">
                Pemantauan kuota maksimal beban aktif 20 Story Points per programmer dalam periode terpilih.
            </p>
        </div>

        <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <select name="month" onchange="this.form.submit()"
                class="text-xs bg-white border-[#e2e8f0] rounded-md shadow-sm focus:ring-primary/20 focus:border-primary text-[#1e293b]">
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
                class="text-xs bg-white border-[#e2e8f0] rounded-md shadow-sm focus:ring-primary/20 focus:border-primary text-[#1e293b]">
                @php $currentYear = now()->year; @endphp
                @for ($y = $currentYear - 2; $y <= $currentYear + 2; $y++)
                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <span
                class="text-[11px] font-mono font-bold bg-primary/10 text-primary px-3 py-2 rounded-md border border-primary/20 shadow-sm uppercase tracking-wider ml-0 lg:ml-2">
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
                class="p-4 bg-[#f8fafc] rounded-xl border border-[#e2e8f0] shadow-sm transition hover:shadow-md">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h5 class="font-bold text-sm text-[#1e293b]">{{ $prog['name'] }}</h5>
                        <p class="text-[10px] font-medium tracking-wide mt-0.5 text-[#64748b] uppercase">
                            Status: <span
                                class="{{ $prog['percentage'] >= 75 ? 'text-amber-500 font-semibold' : 'text-green-500' }}">{{ $statusText }}</span>
                        </p>
                    </div>
                    <span
                        class="text-xs font-bold font-mono px-2.5 py-0.5 rounded-full {{ $prog['percentage'] >= 100 ? 'bg-red-50 text-red-700' : 'bg-primary/10 text-primary' }}">
                        {{ $prog['active_points'] == (int) $prog['active_points'] ? (int) $prog['active_points'] : number_format($prog['active_points'], 1) }}
                        / {{ $prog['max_capacity'] }} Pts </span>
                </div>

                <div class="w-full bg-[#e2e8f0] rounded-full h-3 overflow-hidden shadow-inner">
                    <div class="h-full rounded-full transition-all duration-500 {{ $prog['percentage'] >= 100 ? 'bg-red-500 animate-pulse' : ($prog['percentage'] >= 75 ? 'bg-amber-500' : 'bg-[#3730a3]') }}"
                        style="width: {{ $prog['percentage'] }}%">
                    </div>
                </div>

                <div
                    class="flex justify-between items-center text-[10px] text-[#64748b] mt-2 font-medium">
                    <span>Sisa Slot Kuota: {{ max($prog['max_capacity'] - $prog['active_points'], 0) }} Story
                        Points</span>
                    <span>{{ round($prog['percentage']) }}% Terpakai</span>
                </div>
            </div>
        @empty
            <div
                class="col-span-1 md:col-span-2 text-center py-6 bg-[#f8fafc] rounded-xl border border-dashed border-[#e2e8f0]">
                <p class="text-xs text-[#64748b] italic">Data profil kapasitas programmer belum tersedia di master data.
                </p>
            </div>
        @endforelse
    </div>
</div>
