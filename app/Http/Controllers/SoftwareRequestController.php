<?php

namespace App\Http\Controllers;

use App\Enums\RequestStatus;
use App\Enums\ProjectStatus;
use App\Models\Programmer;
use App\Models\SoftwareRequest;
use App\Models\DevelopmentProject;
use App\Models\Application;
use App\Models\ProjectHistoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule\Enum;
use Carbon\Carbon;

class SoftwareRequestController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'user') {
            $requests = SoftwareRequest::with(['unit', 'application'])
                ->where('user_id', auth()->id())
                ->latest('ticket_number')
                ->paginate(10);
        } else {
            $requests = SoftwareRequest::with(['unit', 'application'])
                ->latest('ticket_number')
                ->paginate(10);
        }

        return view('requests.index', compact('requests'));
    }

    public function create()
    {
        $applications = Application::where('status', 'active')->get();
        return view('requests.create', compact('applications'));
    }

    public function store(Request $request)
    {
        // validasi input
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'request_type'   => 'required|in:new_feature,modification,bug',
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'business_impact' => 'required|string',
            'target_used_date' => 'required|date|after:today',
            'attachment'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Otomatisasi Pembuatan Nomor Tiket (REQ-2026-XXX)
        $year = date('Y');
        $lastRequest = SoftwareRequest::whereYear('created_at', $year)->latest('ticket_number')->first();

        if ($lastRequest) {
            $lastNumber = (int) substr($lastRequest->ticket_number, -3);
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }
        $ticketNumber = "REQ-{$year}-{$nextNumber}";

        // Menangani Upload File Lampiran (Jika ada)
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        // simpan ke database
        SoftwareRequest::create([
            'ticket_number' => $ticketNumber,
            'user_id' => auth()->id(),
            'unit_id' => auth()->user()->unit_id,
            'application_id' => $request->application_id,
            'request_type' => $request->request_type,
            'title' => $request->title,
            'description' => $request->description,
            'business_impact' => $request->business_impact,
            'target_used_date' => $request->target_used_date,
            'attachment_path' => $attachmentPath,
            'status' => RequestStatus::SUBMITTED->value,
        ]);

        return redirect()->route('requests.index')->with('success', 'Pengajuan SDRF berhasil dibuat dengan nomor tiket ' . $ticketNumber);
    }

    public function show($id)
    {
        $requestData = SoftwareRequest::with(['unit', 'application', 'user'])->findOrFail($id);

        $applications = Application::where('status', 'active')->get();

        $programmers = Programmer::with('user')
            ->withSum(['developmentProjects as active_points' => function ($query) {
                $query->where('is_active_load', true);
            }], 'story_points')
            ->get();

        return view('requests.show', compact('requestData', 'applications', 'programmers'));
    }

    public function viewFile($id)
    {
        $requestData = SoftwareRequest::findOrFail($id);

        if (!$requestData->attachment_path) {
            abort(404, 'File tidak ditemukan.');
        }

        $path = storage_path('app/public/' . $requestData->attachment_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan di server.');
        }

        $mimeType = mime_content_type($path);

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }

    public function review(Request $request, $id)
    {
        $requestData = SoftwareRequest::findOrFail($id);
        $oldStatus = $requestData->status;

        // --- KONDISI 1: PENGESAHAN PROYEK (APPROVE PROJECT) ---
        if ($request->action === 'approve_project') {

            // 1. Validasi Utama Laravel
            $request->validate([
                'meeting_notes' => 'required|string',
                'programmer_id' => 'required|exists:programmers,id',
                't_shirt_size'  => 'required|in:S,M,L,XL',
                'start_date'    => 'required|date',
                'end_date'      => 'required|date|after_or_equal:start_date',
            ]);

            // 2. Hitung Selisih Hari Nyata
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $days = $start->diffInDays($end) + 1;

            // 3. Validasi Durasi T-shirt Size (Aturan waktu pengerjaan yang sudah Anda kunci)
            switch ($request->t_shirt_size) {
                case 'S':
                    if ($days < 1 || $days > 3) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['end_date' => "Gagal Sahkan! Ukuran S wajib diselesaikan dalam 1 hingga 3 hari (Input Anda: {$days} hari)."]);
                    }
                    break;
                case 'M':
                    if ($days < 7 || $days > 14) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['end_date' => "Gagal Sahkan! Ukuran M wajib diselesaikan dalam 1 hingga 2 minggu / 7-14 hari (Input Anda: {$days} hari)."]);
                    }
                    break;
                case 'L':
                    if ($days < 21 || $days > 28) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['end_date' => "Gagal Sahkan! Ukuran L wajib diselesaikan dalam 3 hingga 4 minggu / 21-28 hari (Input Anda: {$days} hari)."]);
                    }
                    break;
            }

            // 4. Hitung Kapasitas & Simulasi Poin untuk Manajemen Konflik
            $programmerId = $request->programmer_id;
            $tShirtSize = $request->t_shirt_size;
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            $pointsMap = ['S' => 2, 'M' => 5, 'L' => 10, 'XL' => 20];
            $newProjectPoints = $pointsMap[$tShirtSize] ?? 0;

            $bulanTarget = $startDate->month;
            $tahunTarget = $startDate->year;

            $monthStart = Carbon::createFromDate($tahunTarget, $bulanTarget, 1)->startOfMonth();
            $monthEnd = Carbon::createFromDate($tahunTarget, $bulanTarget, 1)->endOfMonth();

            $activeProjects = DevelopmentProject::where('programmer_id', $programmerId)
                ->where('is_active_load', true)
                ->whereNotNull('start_date')
                ->whereNotNull('end_date')
                ->where('start_date', '<=', $monthEnd)
                ->where('end_date', '>=', $monthStart)
                ->get();

            $currentActivePoints = $activeProjects->sum(function ($project) use ($bulanTarget, $tahunTarget) {
                return $project->calculatePointsForMonth($bulanTarget, $tahunTarget);
            });

            $simulasiTotalPoin = $currentActivePoints + $newProjectPoints;

            // 5. Cek Over-Capacity (> 20 Pts)
            if ($simulasiTotalPoin > 20) {
                // Jika melebihi batas poin tapi Kepala TIK belum mencentang checkbox urgent
                if (!$request->has('is_urgent')) {
                    return redirect()->back()->withErrors([
                        'programmer_id' => 'Programmer mengalami over-capacity (' . $simulasiTotalPoin . ' Pts). Harap centang Protokol Manajemen Konflik (Urgent Case) untuk melanjutkan.'
                    ])->withInput();
                }

                // Eksekusi Jalur Transaksi: Manajemen Konflik (CLOSE_SUSPENDED + Kloning Sisa Poin)
                DB::transaction(function () use ($activeProjects, $startDate, $requestData, $programmerId, $newProjectPoints, $tShirtSize, $endDate, $request) {

                    // Ambil proyek berjalan pertama milik programmer tersebut untuk ditangguhkan
                    $projectToSuspend = $activeProjects->first();

                    if ($projectToSuspend) {
                        $originalPoints = $projectToSuspend->story_points;
                        $originalEndDate = $projectToSuspend->end_date;
                        $bulanProyekLama = Carbon::parse($projectToSuspend->start_date)->month;
                        $tahunProyekLama = Carbon::parse($projectToSuspend->start_date)->year;

                        // Menguji akumulasi poin terpakai sampai dengan sebelum proyek baru dimulai
                        $projectToSuspend->end_date = $startDate->format('Y-m-d');
                        $pointsUsed = $projectToSuspend->calculatePointsForMonth($bulanProyekLama, $tahunProyekLama);

                        // Tutup paksa proyek lama di hari pertama proyek baru berjalan
                        $projectToSuspend->project_status = ProjectStatus::CLOSE_SUSPENDED;
                        $projectToSuspend->is_active_load = false; // Kuota dibebaskan
                        $projectToSuspend->save();

                        // Hitung sisa poin pekerjaan yang belum diselesaikan (pembulatan ke atas)
                        $pointsRemaining = max((int) ceil($originalPoints - $pointsUsed), 1);

                        // Buat Log Audit Penangguhan Proyek Lama
                        ProjectHistoryLog::create([
                            'software_request_id'    => $projectToSuspend->software_request_id,
                            'development_project_id' => $projectToSuspend->id,
                            'user_id'                => auth()->id(),
                            'action_type'            => 'CLOSE_SUSPENDED',
                            'old_value'              => 'End Date Asli: ' . Carbon::parse($originalEndDate)->format('d-m-Y'),
                            'new_value'              => 'Dipotong ke Tanggal: ' . $startDate->format('d-m-Y'),
                            'reason'                 => "Proyek ditangguhkan darurat oleh Kepala TIK karena penugasan proyek baru senilai {$newProjectPoints} Pts. Sisa beban {$pointsRemaining} Pts dialihkan ke proyek kloning baru.",
                        ]);

                        // Lahirkan Proyek Baru hasil kloningan sisa Suspend (Fase 2)
                        $cleanTitle = str_replace(['[SISA SUSPEND:', 'Pts] - '], '', $projectToSuspend->phase_title);
                        $clonedProject = DevelopmentProject::create([
                            'software_request_id' => $projectToSuspend->software_request_id,
                            'programmer_id'       => $programmerId,
                            't_shirt_size'        => $projectToSuspend->t_shirt_size,
                            'phase_title'         => "[SISA SUSPEND: {$pointsRemaining} Pts] - " . $cleanTitle,
                            'story_points'        => $pointsRemaining, // Kunci nilai sisa di story_points
                            'start_date'          => null, // Mengambang tanpa tanggal agar siap di-reschedule nanti
                            'end_date'            => null,
                            'project_status'      => ProjectStatus::WAITING,
                            'is_active_load'      => true,
                        ]);

                        // Buat Log Audit Kelahiran Proyek Kloning Sisa Pekerjaan
                        ProjectHistoryLog::create([
                            'software_request_id'    => $clonedProject->software_request_id,
                            'development_project_id' => $clonedProject->id,
                            'user_id'                => auth()->id(),
                            'action_type'            => 'SUSPEND_CLONED',
                            'old_value'              => 'ID Proyek Induk: ' . $projectToSuspend->id,
                            'new_value'              => 'Proyek Kloning Sisa Tercipta',
                            'reason'                 => "Sistem melahirkan proyek baru sisa pekerjaan sebesar {$pointsRemaining} Pts dalam antrean WAITING.",
                        ]);
                    }

                    // Terbitkan Proyek Baru Urgent
                    $newProject = DevelopmentProject::create([
                        'software_request_id' => $requestData->id,
                        'programmer_id'       => $programmerId,
                        't_shirt_size'        => $tShirtSize,
                        'phase_title'         => $requestData->title,
                        'story_points'        => $newProjectPoints,
                        'start_date'          => $startDate->format('Y-m-d'),
                        'end_date'            => $endDate->format('Y-m-d'),
                        'project_status'      => ProjectStatus::WAITING,
                        'is_active_load'      => true,
                    ]);

                    // Update Software Request utama menjadi APPROVED
                    $requestData->update([
                        'status' => RequestStatus::APPROVED->value,
                        'meeting_notes' => $requestData->meeting_notes . "\n" . 'Catatan Pengesahan (Intervensi Darurat): ' . $request->meeting_notes
                    ]);

                    // Log persetujuan lewat Manajemen Konflik
                    ProjectHistoryLog::create([
                        'software_request_id'    => $requestData->id,
                        'development_project_id' => $newProject->id,
                        'user_id'                => auth()->id(),
                        'action_type'            => 'APPROVED_WITH_CONFLICT',
                        'old_value'              => $oldStatus->value ?? 'analysis_scheduled',
                        'new_value'              => RequestStatus::APPROVED->value,
                        'reason'                 => "Disahkan lewat jalur intervensi Manajemen Konflik (Urgent Case). Proyek berjalan berhasil ditangguhkan demi tugas darurat.",
                    ]);
                });

                return redirect()->route('requests.index')->with('success', 'Protokol Manajemen Konflik berhasil dijalankan! Proyek lama disuspend, sisa poin aman dikloning, dan SDRF Berhasil disahkan.');
            }

            // 6. Jalur Normal (Jika simulasiTotalPoin <= 20)
            DB::transaction(function () use ($requestData, $programmerId, $tShirtSize, $newProjectPoints, $startDate, $endDate, $request, $oldStatus) {
                $project = DevelopmentProject::create([
                    'software_request_id' => $requestData->id,
                    'programmer_id'       => $programmerId,
                    't_shirt_size'        => $tShirtSize,
                    'phase_title'         => $requestData->title,
                    'story_points'        => $newProjectPoints,
                    'start_date'          => $startDate->format('Y-m-d'),
                    'end_date'            => $endDate->format('Y-m-d'),
                    'project_status'      => ProjectStatus::WAITING,
                    'is_active_load'      => true,
                ]);

                $requestData->update([
                    'status' => RequestStatus::APPROVED->value,
                    'meeting_notes' => $requestData->meeting_notes . "\n" . 'Catatan Pengesahan: ' . $request->meeting_notes
                ]);

                ProjectHistoryLog::create([
                    'software_request_id'    => $requestData->id,
                    'development_project_id' => $project->id,
                    'user_id'                => auth()->id(),
                    'action_type'            => 'STATUS_CHANGE',
                    'old_value'              => $oldStatus->value ?? 'analysis_scheduled',
                    'new_value'              => RequestStatus::APPROVED->value,
                    'reason'                 => "Permintaan aplikasi disetujui secara normal dan alokasi proyek terjadwal dengan aman.",
                ]);
            });

            return redirect()->route('requests.index')->with('success', 'SDRF Berhasil disahkan!');
        }

        // --- KONDISI 2: PENJADWALAN RAPAT (SCHEDULE MEETING) ---
        if ($request->action === 'schedule_meeting') {
            $request->validate(['meeting_notes' => 'required|string']);
            $requestData->update([
                'status' => RequestStatus::ANALYSIS_SCHEDULED->value,
                'meeting_notes' => 'Catatan Awal: ' . $request->meeting_notes
            ]);
            return redirect()->route('requests.index')->with('success', 'Rapat berhasil dijadwalkan.');
        }

        // --- KONDISI 3: PENOLAKAN PENGAJUAN (REJECT) ---
        if ($request->action === 'reject') {
            $request->validate(['meeting_notes' => 'required|string']);
            $requestData->update([
                'status' => RequestStatus::REJECTED->value,
                'meeting_notes' => 'Penolakan: ' . $request->meeting_notes
            ]);
            return redirect()->route('requests.index')->with('success', 'Permintaan resmi ditolak.');
        }
    }

    public function review_old(Request $request, $id)
    {
        $requestData = SoftwareRequest::findOrFail($id);
        $oldStatus = $requestData->status;

        if ($request->action === 'approve_project') {

            // 1. Validasi Utama Laravel
            $request->validate([
                'meeting_notes' => 'required|string',
                'programmer_id' => 'required|exists:programmers,id',
                't_shirt_size'  => 'required|in:S,M,L,XL',
                'start_date'    => 'required|date',
                'end_date'      => 'required|date|after_or_equal:start_date',
            ]);

            // 2. Hitung Selisih Hari Nyata
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $days = $start->diffInDays($end) + 1;

            // 3. Validasi Durasi T-shirt Size (Melempar error spesifik ke end_date)
            switch ($request->t_shirt_size) {
                case 'S':
                    if ($days < 1 || $days > 3) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['end_date' => "Gagal Sahkan! Ukuran S wajib diselesaikan dalam 1 hingga 3 hari (Input Anda: {$days} hari)."]);
                    }
                    break;
                case 'M':
                    if ($days < 7 || $days > 14) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['end_date' => "Gagal Sahkan! Ukuran M (1-2 Minggu) wajib diselesaikan dalam 7 hingga 14 hari (Input Anda: {$days} hari)."]);
                    }
                    break;
                case 'L':
                    if ($days < 21 || $days > 28) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['end_date' => "Gagal Sahkan! Ukuran L (3-4 Minggu) wajib diselesaikan dalam 21 hingga 28 hari (Input Anda: {$days} hari)."]);
                    }
                    break;
                case 'XL':
                    if ($days < 28) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['end_date' => "Gagal Sahkan! Ukuran XL wajib memiliki durasi kerja minimal 28 hari atau lebih (Input Anda: {$days} hari)."]);
                    }
                    break;
            }

            // --- Sisa Logic Poin, Over-capacity, dan Save Proyek ---
            $tShirtPoints = ['S' => 2, 'M' => 5, 'L' => 10, 'XL' => 20];
            $newProjectPoints = $tShirtPoints[$request->t_shirt_size];

            $bulanTarget = Carbon::parse($request->start_date)->month;
            $tahunTarget = Carbon::parse($request->start_date)->year;

            $projectsInMonth = DevelopmentProject::where('programmer_id', $request->programmer_id)
                ->where('is_active_load', true)
                ->get();

            $currentActivePoints = 0;
            foreach ($projectsInMonth as $project) {
                $currentActivePoints += $project->calculatePointsForMonth($bulanTarget, $tahunTarget);
            }

            $totalBebanMendatang = $currentActivePoints + $newProjectPoints;

            if ($totalBebanMendatang > 20 && !$request->has('is_urgent')) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'programmer_id' => "Gagal Sahkan! Beban kerja programmer pada bulan tersebut melebihi 20 Poin (Prediksi: {$totalBebanMendatang} Pts). Centang Manajemen Konflik jika ini darurat."
                    ]);
            }

            if ($totalBebanMendatang > 20 && $request->has('is_urgent')) {
                foreach ($projectsInMonth as $oldProject) {
                    if ($oldProject->calculatePointsForMonth($bulanTarget, $tahunTarget) > 0) {
                        $oldProject->update([
                            'project_status' => ProjectStatus::SUSPENDED,
                            'is_active_load' => false
                        ]);

                        ProjectHistoryLog::create([
                            'software_request_id' => $oldProject->software_request_id,
                            'development_project_id' => $oldProject->id,
                            'user_id' => auth()->id(),
                            'action_type' => 'PROJECT_SUSPENDED',
                            'old_value' => ProjectStatus::IN_DEVELOPMENT->value,
                            'new_value' => ProjectStatus::SUSPENDED->value,
                            'reason' => "Ditangguhkan otomatis karena programmer dialihkan ke proyek URGENT tiket: " . $requestData->ticket_number,
                        ]);
                    }
                }
            }

            DevelopmentProject::create([
                'software_request_id' => $requestData->id,
                'programmer_id'       => $request->programmer_id,
                't_shirt_size'        => $request->t_shirt_size,
                'phase_title'         => $requestData->title,
                'story_points'        => $newProjectPoints,
                'start_date'          => $request->start_date,
                'end_date'            => $request->end_date,
                'project_status'      => ProjectStatus::WAITING,
                'is_active_load'      => true,
            ]);

            $requestData->update([
                'status' => RequestStatus::APPROVED->value,
                'meeting_notes' => $requestData->meeting_notes . "\n" . 'Catatan Pengesahan: ' . $request->meeting_notes
            ]);

            return redirect()->route('requests.index')->with('success', 'SDRF Berhasil disahkan!');
        }

        // --- Alur Pertemuan / Tolak ---
        if ($request->action === 'schedule_meeting') {
            $request->validate(['meeting_notes' => 'required|string']);
            $requestData->update(['status' => RequestStatus::ANALYSIS_SCHEDULED->value, 'meeting_notes' => 'Catatan Awal: ' . $request->meeting_notes]);
            return redirect()->route('requests.index')->with('success', 'Rapat berhasil dijadwalkan.');
        }

        if ($request->action === 'reject') {
            $request->validate(['meeting_notes' => 'required|string']);
            $requestData->update(['status' => RequestStatus::REJECTED->value, 'meeting_notes' => 'Penolakan: ' . $request->meeting_notes]);
            return redirect()->route('requests.index')->with('success', 'Permintaan resmi ditolak.');
        }

        return redirect()->back()->with('error', 'Aksi tidak dikenali.');
    }

    public function getProgrammerWorkload(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $bulanTarget = $startDate->month;
        $tahunTarget = $startDate->year;

        $monthStart = Carbon::createFromDate($tahunTarget, $bulanTarget, 1)->startOfMonth();
        $monthEnd = Carbon::createFromDate($tahunTarget, $bulanTarget, 1)->endOfMonth();

        $programmers = Programmer::with('user')->get()->map(function ($prog) use ($bulanTarget, $tahunTarget, $monthStart, $monthEnd) {

            $activeProjects = DevelopmentProject::where('programmer_id', $prog->id)
                ->where('is_active_load', true)
                ->whereNotNull('start_date')
                ->whereNotNull('end_date')
                ->where('start_date', '<=', $monthEnd)
                ->where('end_date', '>=', $monthStart)
                ->get();

            $activePoints = $activeProjects->sum(function ($project) use ($bulanTarget, $tahunTarget) {
                return $project->calculatePointsForMonth($bulanTarget, $tahunTarget);
            });

            $proyekBerjalan = $activeProjects->first();

            return [
                'id' => $prog->id,
                'name' => $prog->user ? $prog->user->name : 'Tanpa Nama',
                'active_points' => (int) $activePoints,
                'potential_suspend_title' => $proyekBerjalan ? $proyekBerjalan->phase_title : null,
                'potential_suspend_points' => $proyekBerjalan ? (int) $proyekBerjalan->calculatePointsForMonth($bulanTarget, $tahunTarget) : 0,
            ];
        });

        return response()->json($programmers);
    }
}
