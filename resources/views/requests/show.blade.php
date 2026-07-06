<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                Detail Pengajuan: {{ $requestData->ticket_number }}
            </h2>
            <a href="{{ route('requests.index') }}"
                class="text-sm text-slate-500 hover:underline">&larr; Kembali</a>
        </div>
    </x-slot>

    <div class="space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-50 border-0 text-success rounded-xl text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="bg-white overflow-hidden shadow-soft rounded-2xl p-6 text-slate-700 border-0">
                        <h3 class="text-lg font-bold border-b pb-3 border-gray-100 text-slate-800">Informasi
                            Pengajuan</h3>

                        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-slate-400 block text-xs font-bold uppercase tracking-wider">Pengaju / Unit Kerja</span>
                                <strong class="text-slate-700">{{ $requestData->user->username }} / {{ $requestData->unit->name }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-xs font-bold uppercase tracking-wider">Aplikasi Target / Tipe</span>
                                <strong class="capitalize text-slate-700">{{ $requestData->application->name }}
                                    ({{ str_replace('_', ' ', $requestData->request_type) }})</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-xs font-bold uppercase tracking-wider">Tanggal Target Digunakan</span>
                                <strong
                                    class="text-primary">{{ \Carbon\Carbon::parse($requestData->target_used_date)->format('d M Y') }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-xs font-bold uppercase tracking-wider">Status Saat Ini</span>
                                <span
                                    class="inline-block text-xs font-bold px-2.5 py-0.5 rounded-1.8 mt-1 bg-yellow-100 text-yellow-800">
                                    {{ strtoupper($requestData->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div>
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Judul Permintaan</h4>
                                <p class="text-md mt-1 font-semibold text-slate-800">{{ $requestData->title }}</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Deskripsi Masalah / Kebutuhan</h4>
                                <p
                                    class="text-sm mt-1 text-slate-600 bg-slate-50 p-3 rounded-xl whitespace-pre-line border border-gray-100">
                                    {{ $requestData->description }}</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Dampak Bisnis & Justifikasi</h4>
                                <p
                                    class="text-sm mt-1 text-slate-600 bg-slate-50 p-3 rounded-xl whitespace-pre-line border border-gray-100">
                                    {{ $requestData->business_impact }}</p>
                            </div>
                            @if ($requestData->attachment_path)
                                <div>
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider font-semibold">Dokumen Pendukung</h4>
                                    <a href="{{ route('requests.view-file', $requestData->id) }}" target="_blank"
                                        class="mt-2 inline-flex items-center text-sm font-semibold text-primary hover:text-primary-light transition-colors">
                                        📁 Lihat Lampiran Berkas
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-6">

                    @if ($requestData->status === 'submitted')
                        @if (auth()->user()->role === 'kepala_tik')
                            <div
                                class="bg-white overflow-hidden shadow-soft rounded-2xl p-6 text-slate-700 border-0 border-l-4 border-blue-500">
                                <h3 class="text-md font-bold text-blue-600 mb-4">Panel Validasi & Penjadwalan</h3>

                                <form method="POST" action="{{ route('requests.review', $requestData->id) }}"
                                    class="space-y-4">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <x-input-label for="action" :value="__('Keputusan Triage Awal')" class="font-bold text-xs text-slate-700" />
                                        <select id="action" name="action"
                                            class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm"
                                            required>
                                            <option value="">-- Pilih Keputusan --</option>
                                            <option value="schedule_meeting">Setujui & Jadwalkan Pertemuan</option>
                                            <option value="reject">Tolak Permintaan (Rejected)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="meeting_notes" :value="__('Catatan / Alasan Penolakan')" class="font-bold text-xs text-slate-700" />
                                        <textarea id="meeting_notes" name="meeting_notes" rows="3"
                                            class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm"
                                            placeholder="Masukkan detail alasan jika ditolak, atau catatan awal jika dijadwalkan rapat..." required></textarea>
                                    </div>

                                    <x-primary-button class="w-full justify-center bg-primary hover:bg-primary/95 shadow-soft-sm rounded-lg">
                                        {{ __('Proses Keputusan') }}
                                    </x-primary-button>
                                </form>
                            </div>
                        @else
                            <div
                                class="bg-white overflow-hidden shadow-soft rounded-2xl p-6 text-slate-400 text-center text-sm border-0">
                                ⏳ Menunggu validasi awal dan triage dari Kepala TIK.
                            </div>
                        @endif
                    @endif


                    @if ($requestData->status === 'analysis_scheduled')
                        @if (auth()->user()->role === 'kepala_tik')
                            <div
                                class="bg-white overflow-hidden shadow-soft rounded-2xl p-6 text-slate-700 border-0 border-l-4 border-green-500">
                                <h3 class="text-md font-bold text-green-600 mb-4 font-semibold">Panel Komitmen Proyek & Jadwal</h3>

                                <form method="POST" action="{{ route('requests.review', $requestData->id) }}"
                                    class="space-y-4">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="action" value="approve_project">

                                    <div>
                                        <x-input-label for="meeting_notes" :value="__('Hasil Notulensi Rapat & Target Scope')" class="font-bold text-xs text-slate-700" />
                                        <textarea id="meeting_notes" name="meeting_notes" rows="3"
                                            class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm"
                                            placeholder="Tulis hasil kesepakatan scope akhir dengan user..." required>{{ old('meeting_notes', $requestData->meeting_notes) }}</textarea>
                                    </div>

                                    <div>
                                        <x-input-label for="t_shirt_size" :value="__('Estimasi Bobot Kerja (T-Shirt Size)')" class="font-bold text-xs text-slate-700" />
                                        <select id="t_shirt_size" name="t_shirt_size"
                                            class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm"
                                            required>
                                            <option value="">-- Pilih Ukuran --</option>
                                            <option value="S">S (Small - 2 Poin | 1-3 Hari)</option>
                                            <option value="M">M (Medium - 5 Poin | 1-2 Minggu)</option>
                                            <option value="L">L (Large - 10 Poin | 3-4 Minggu)</option>
                                            <option value="XL">XL (Extra Large - 20 Poin | 4 Minggu)</option>
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="start_date" :value="__('Tanggal Mulai')" class="font-bold text-xs text-slate-700" />
                                            <x-text-input id="start_date" name="start_date" type="date"
                                                class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm {{ $errors->has('start_date') ? 'border-red-500 ring-1 ring-red-500' : '' }}"
                                                value="{{ old('start_date') }}" required />
                                            @error('start_date')
                                                <p class="mt-1 text-xs text-red-600 font-semibold">
                                                    {{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <x-input-label for="end_date" :value="__('Tanggal Selesai')" class="font-bold text-xs text-slate-700" />
                                            <x-text-input id="end_date" name="end_date" type="date"
                                                class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm {{ $errors->has('end_date') ? 'border-red-500 ring-1 ring-red-500' : '' }}"
                                                value="{{ old('end_date') }}" required />

                                            @error('end_date')
                                                <p
                                                    class="mt-1 text-xs text-red-600 font-bold bg-red-50 p-1.5 rounded-lg border border-red-200">
                                                    ⚠️ {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div id="api_error_message"
                                        class="mt-2 p-2.5 bg-red-50 border border-red-200 text-red-600 rounded-lg text-xs font-semibold hidden animate-pulse">
                                    </div>

                                    <div>
                                        <x-input-label for="programmer_id" :value="__('Tugaskan Programmer (Beban Kerja Terjadwal)')" class="font-bold text-xs text-slate-700" />

                                        @error('programmer_id')
                                            <div
                                                class="mt-1 p-3 bg-red-50 text-red-700 rounded-lg text-sm font-semibold animate-pulse">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                        <select id="programmer_id" name="programmer_id"
                                            class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm text-sm font-sans"
                                            required disabled>
                                            <option value="">-- Isi tanggal pengerjaan terlebih dahulu --</option>
                                        </select>
                                        <p id="suspension_warning"
                                            class="text-xs text-red-500 mt-1 hidden font-semibold"></p>
                                    </div>

                                    <div id="urgent_container"
                                        class="bg-red-50 p-3 rounded-lg border border-red-100 mt-2 hidden">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="is_urgent" name="is_urgent" type="checkbox"
                                                    value="1"
                                                    class="focus:ring-primary h-4 w-4 text-primary border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="is_urgent"
                                                    class="font-bold text-red-800">🚨 Aktifkan Manajemen Konflik</label>
                                                <p class="text-xs text-red-600">Menunda proyek terpilih demi proyek darurat ini.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <x-primary-button
                                        class="w-full justify-center bg-green-600 hover:bg-green-700 shadow-soft-sm rounded-lg">
                                        {{ __('Sahkan & Buat Proyek') }}
                                    </x-primary-button>
                                </form>
                            </div>
                        @else
                            <div
                                class="bg-white overflow-hidden shadow-soft rounded-2xl p-6 text-slate-700 border-0">
                                <h3 class="text-sm font-bold text-primary mb-2">📅 Rapat Koordinasi Dijadwalkan</h3>
                                <p class="text-sm text-slate-500">Permintaan Anda sedang ditinjau intensif. Mohon siapkan waktu untuk menghadiri koordinasi teknis bersama tim TIK.</p>
                            </div>
                        @endif
                    @endif


                    @if ($requestData->status !== 'submitted' && $requestData->meeting_notes)
                        <div
                            class="bg-white overflow-hidden shadow-soft rounded-2xl p-6 text-slate-700 border-0">
                            <h3 class="text-xs font-bold text-slate-400 mb-2 uppercase tracking-wider">📋 Catatan Keputusan / Notulensi Rapat TIK</h3>
                            <p
                                class="text-sm bg-slate-50 p-3 rounded-xl font-mono text-xs border border-gray-100">
                                {{ $requestData->meeting_notes }}</p>
                        </div>
                    @endif

                </div>
            </div>

    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const programmerSelect = document.getElementById('programmer_id');
        const warningText = document.getElementById('suspension_warning');
        const urgentContainer = document.getElementById('urgent_container');

        @if ($errors->any())
            const btnReview = Array.from(document.querySelectorAll('button')).find(el => el.textContent
                .includes('Sahkan') || el.textContent.includes('Review'));
            if (btnReview) {
                btnReview.click();
            }
        @endif

        if (!startDateInput || !endDateInput || !programmerSelect) {
            return;
        }

        const apiErrorMessage = document.getElementById('api_error_message');

        function fetchWorkLoad() {
            const start = startDateInput.value;
            const end = endDateInput.value;

            if (!start || !end) return;

            // Bersihkan dropdown, kunci sementara, dan sembunyikan error
            programmerSelect.innerHTML = '<option value="">Sedang menghitung beban kerja tim...</option>';
            programmerSelect.disabled = true;
            warningText.classList.add('hidden');
            urgentContainer.classList.add('hidden');
            if (apiErrorMessage) {
                apiErrorMessage.classList.add('hidden');
                apiErrorMessage.textContent = '';
            }

            fetch("{{ route('requests.api.programmer-workload') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json" // Memaksa Laravel merespons dengan JSON jika ada error validasi
                    },
                    body: JSON.stringify({
                        start_date: start,
                        end_date: end
                    })
                })
                .then(async response => {
                    // Jika server mengembalikan status error (500, 422, 404, dll)
                    if (!response.ok) {
                        let errMsg = "Server mengembalikan error.";
                        try {
                            const errorJson = await response.json();
                            if (errorJson.errors) {
                                errMsg = Object.values(errorJson.errors).flat().join(" ");
                            } else if (errorJson.message) {
                                errMsg = errorJson.message;
                            }
                        } catch (e) {
                            errMsg =
                                "Gagal memuat data beban kerja. Pastikan tanggal mulai sebelum tanggal selesai.";
                        }
                        throw new Error(errMsg);
                    }
                    return response.json();
                })
                .then(data => {
                    programmerSelect.innerHTML = '<option value="">-- Pilih Developer --</option>';
                    programmerSelect.disabled = false;
                    window.programmerDataCache = data;

                    data.forEach(prog => {
                        const option = document.createElement('option');
                        option.value = prog.id;

                        let label =
                            `${prog.name} [Beban target bulan ini: ${prog.active_points} / 20 Pts]`;
                        if (prog.active_points >= 20) {
                            label += ' - ⚠️ Penuh';
                        }
                        option.textContent = label;
                        programmerSelect.appendChild(option);
                    });
                })
                .catch(err => {
                    programmerSelect.innerHTML = '<option value="">Gagal memuat data beban kerja.</option>';
                    console.error("Detail Fetch Error:", err);
                    if (apiErrorMessage) {
                        apiErrorMessage.textContent = "⚠️ " + err.message;
                        apiErrorMessage.classList.remove('hidden');
                    }
                });
        }

        startDateInput.addEventListener('change', fetchWorkLoad);
        endDateInput.addEventListener('change', fetchWorkLoad);

        programmerSelect.addEventListener('change', function() {
            const selectedId = parseInt(this.value);
            const tShirtSelect = document.getElementById('t_shirt_size');

            if (!selectedId || !window.programmerDataCache) return;

            const tShirtPoints = {
                'S': 2,
                'M': 5,
                'L': 10,
                'XL': 20
            };
            const selectedPoints = tShirtPoints[tShirtSelect.value] || 0;

            const currentProg = window.programmerDataCache.find(p => p.id === selectedId);

            if (currentProg) {
                const simulasiTotalPoin = currentProg.active_points + selectedPoints;

                if (simulasiTotalPoin > 20) {

                    let pesanWarning =
                        `🚨 <strong>Rekomendasi Sistem (Over-Capacity):</strong> Pengerjaan proyek baru membuat total beban kerja menjadi ${simulasiTotalPoin} Pts (Melebihi Batas maksimal 20 Pts).<br>`;

                    if (currentProg.potential_suspend_title) {
                        pesanWarning +=
                            `Sistem merekomendasikan untuk menangguhkan/menggeser proyek terjadwal: <strong>"${currentProg.potential_suspend_title}" (${currentProg.potential_suspend_points} Pts)</strong> agar kuota kembali aman.`;
                    } else {
                        pesanWarning +=
                            `Kuota bulan ini sudah penuh/terisi oleh proyek lain. Harap tinjau kembali pembagian tugas.`;
                    }

                    warningText.innerHTML = pesanWarning;
                    warningText.classList.remove('hidden');
                    urgentContainer.classList.remove(
                        'hidden');

                } else {
                    warningText.classList.add('hidden');
                    urgentContainer.classList.add('hidden');
                }
            }
        });
    });
</script>
