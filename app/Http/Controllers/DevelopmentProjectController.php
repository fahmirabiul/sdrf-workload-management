<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Enums\RequestStatus;
use App\Models\DevelopmentProject;
use App\Models\ProjectHistoryLog;
use App\Models\SoftwareRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DevelopmentProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = DevelopmentProject::with(['softwareRequest.user', 'softwareRequest.unit']);

        if ($user->role === 'programmer') {
            if ($user->programmer) {
                // Menampilkan semua proyek milik programmer tersebut, didahulukan yang belum memiliki tanggal (null)
                $projects = $query->where('programmer_id', $user->programmer->id)
                    ->orderByRaw('start_date IS NULL DESC')
                    ->orderBy('start_date', 'asc')
                    ->get();
            } else {
                $projects = collect();
            }
        } else {
            // Kepala TIK/Aktor lain melihat semua proyek, didahulukan yang mengantre tanpa tanggal
            $projects = $query->orderByRaw('start_date IS NULL DESC')->latest()->get();
        }

        return view('projects.index', compact('projects'));
    }

    public function show($id)
    {
        $project = DevelopmentProject::with([
            'softwareRequest.user',
            'softwareRequest.unit',
            'softwareRequest.application'
        ])->findOrFail($id);

        $historyLogs = ProjectHistoryLog::where('software_request_id', $project->software_request_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('projects.show', compact('project', 'historyLogs'));
    }

    public function updateStatus(Request $request, $id)
    {
        $project = DevelopmentProject::findOrFail($id);
        $oldStatus = $project->project_status;

        // KONDISI KHUSUS: JIKA STATUSNYA WAITING DAN DIALOKASIKAN JADWAL BARU OLEH KEPALA TIK
        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
            ]);

            // Hitung Selisih Hari Nyata
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $days = $start->diffInDays($end) + 1;

            // CEK APAKAH INI PROYEK SISA SUSPEND (Berdasarkan prefix judul fase)
            $isReschedulePiece = str_starts_with($project->phase_title, '[SISA SUSPEND:');

            // Jika BUKAN proyek sisa suspend, jalankan validasi durasi T-Shirt size yang ketat
            if (!$isReschedulePiece) {
                switch ($project->t_shirt_size) {
                    case 'S':
                        if ($days < 1 || $days > 3) {
                            return redirect()->back()->withErrors(['end_date' => "Gagal Alokasikan! Ukuran S wajib diselesaikan dalam 1 hingga 3 hari (Input Anda: {$days} hari)."]);
                        }
                        break;
                    case 'M':
                        if ($days < 7 || $days > 14) {
                            return redirect()->back()->withErrors(['end_date' => "Gagal Alokasikan! Ukuran M wajib diselesaikan dalam 7-14 hari (Input Anda: {$days} hari)."]);
                        }
                        break;
                    case 'L':
                        if ($days < 21 || $days > 28) {
                            return redirect()->back()->withErrors(['end_date' => "Gagal Alokasikan! Ukuran L wajib diselesaikan dalam 21-28 hari (Input Anda: {$days} hari)."]);
                        }
                        break;
                }
            }

            // Eksekusi penyimpanan tanggal alokasi baru
            DB::transaction(function () use ($project, $request, $oldStatus, $isReschedulePiece) {
                $project->update([
                    'start_date' => $request->start_date,
                    'end_date'   => $request->end_date,
                    'project_status' => ProjectStatus::IN_DEVELOPMENT, // Maju otomatis ke tahap coding
                ]);

                // Catat log transparan ke audit trail
                ProjectHistoryLog::create([
                    'software_request_id'    => $project->software_request_id,
                    'development_project_id' => $project->id,
                    'user_id'                => auth()->id(),
                    'action_type'            => $isReschedulePiece ? 'RESCHEDULING_SUSPEND_PIECE' : 'RESCHEDULING',
                    'old_value'              => 'Belum ditentukan (Mengambang)',
                    'new_value'              => $request->start_date . ' s/d ' . $request->end_date,
                    'reason'                 => $isReschedulePiece
                        ? "Kepala TIK mengaktifkan sisa proyek pecahan senilai {$project->story_points} Pts. Validasi rentang waktu dibebaskan sesuai diskresi operasional."
                        : "Kepala TIK mengalokasikan tanggal resmi untuk proyek antrean.",
                ]);
            });

            return redirect()->route('projects.show', $project->id)->with('success', 'Jadwal pengerjaan proyek berhasil dikunci! Status otomatis aktif ke In Development.');
        }

        // JALUR PERUBAHAN STATUS NORMAL OLEH PROGRAMMER (START CODING / SUBMIT TO UAT)
        $request->validate([
            'status' => 'required|string'
        ]);

        $newStatus = ProjectStatus::from($request->status);

        DB::transaction(function () use ($project, $oldStatus, $newStatus) {
            $project->project_status = $newStatus;
            $project->save();

            ProjectHistoryLog::create([
                'software_request_id'    => $project->software_request_id,
                'development_project_id' => $project->id,
                'user_id'                => auth()->id(),
                'action_type'            => 'STATUS_CHANGE',
                'old_value'              => $oldStatus->value,
                'new_value'              => $newStatus->value,
                'reason'                 => "Status proyek berjalan diperbarui menjadi " . $newStatus->label(),
            ]);
        });

        return redirect()->route('projects.show', $project->id)->with('success', 'Status pengerjaan berhasil diperbarui.');
    }

    public function updateStatusOld(Request $request, $id)
    {
        $project = DevelopmentProject::findOrFail($id);
        $oldStatus = $project->project_status;

        // JIKA STATUSNYA WAITING DAN DIALOKASIKAN JADWAL BARU OLEH KEPALA TIK
        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
            ]);

            // Hitung Selisih Hari Nyata
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $days = $start->diffInDays($end) + 1;

            // Validasi Durasi Sesuai Aturan T-shirt Size yang dikunci
            switch ($project->t_shirt_size) {
                case 'S':
                    if ($days < 1 || $days > 3) {
                        return redirect()->back()->withErrors(['end_date' => "Gagal Alokasikan! Ukuran S wajib diselesaikan dalam 1 hingga 3 hari (Input Anda: {$days} hari)."]);
                    }
                    break;
                case 'M':
                    if ($days < 7 || $days > 14) {
                        return redirect()->back()->withErrors(['end_date' => "Gagal Alokasikan! Ukuran M wajib diselesaikan dalam 7-14 hari (Input Anda: {$days} hari)."]);
                    }
                    break;
                case 'L':
                    if ($days < 21 || $days > 28) {
                        return redirect()->back()->withErrors(['end_date' => "Gagal Alokasikan! Ukuran L wajib diselesaikan dalam 21-28 hari (Input Anda: {$days} hari)."]);
                    }
                    break;
            }

            DB::transaction(function () use ($project, $request, $oldStatus) {
                $project->update([
                    'start_date' => $request->start_date,
                    'end_date'   => $request->end_date,
                    'project_status' => ProjectStatus::IN_DEVELOPMENT, // Otomatis maju ke In Development setelah dijadwalkan ulang
                ]);

                ProjectHistoryLog::create([
                    'software_request_id'    => $project->software_request_id,
                    'development_project_id' => $project->id,
                    'user_id'                => auth()->id(),
                    'action_type'            => 'RESCHEDULING',
                    'old_value'              => 'Belum ditentukan',
                    'new_value'              => $request->start_date . ' s/d ' . $request->end_date,
                    'reason'                 => "Kepala TIK mengalokasikan tanggal resmi untuk sisa proyek penangguhan ini.",
                ]);
            });

            return redirect()->route('projects.show', $project->id)->with('success', 'Jadwal pengerjaan sisa suspend berhasil dikunci! Status otomatis beralih ke In Development.');
        }

        // PERUBAHAN STATUS NORMAL OLEH PROGRAMMER (START CODING / SUBMIT TO UAT)
        $request->validate([
            'status' => 'required|string'
        ]);

        $newStatus = ProjectStatus::from($request->status);

        DB::transaction(function () use ($project, $oldStatus, $newStatus) {
            $project->project_status = $newStatus;
            $project->save();

            ProjectHistoryLog::create([
                'software_request_id'    => $project->software_request_id,
                'development_project_id' => $project->id,
                'user_id'                => auth()->id(),
                'action_type'            => 'STATUS_CHANGE',
                'old_value'              => $oldStatus->value,
                'new_value'              => $newStatus->value,
                'reason'                 => "Status proyek berjalan diperbarui menjadi " . $newStatus->label(),
            ]);
        });

        return redirect()->route('projects.show', $project->id)->with('success', 'Status pengerjaan berhasil diperbarui.');
    }

    public function submitUat(Request $request, $id)
    {
        $project = DevelopmentProject::findOrFail($id);
        $request->validate([
            'uat_status'   => 'required|in:approved,rejected',
            'uat_feedback' => 'required_if:uat_status,rejected|nullable|string'
        ]);

        $oldStatus = $project->project_status;

        DB::transaction(function () use ($project, $request, $oldStatus) {
            if ($request->uat_status === 'approved') {
                $project->update([
                    'project_status' => ProjectStatus::READY_FOR_PRODUCTION,
                    'uat_status'     => 'approved',
                    'uat_feedback'   => $request->uat_feedback
                ]);
            } else {
                $project->update([
                    'project_status' => ProjectStatus::IN_DEVELOPMENT,
                    'uat_status'     => 'rejected',
                    'uat_feedback'   => $request->uat_feedback
                ]);
            }

            ProjectHistoryLog::create([
                'software_request_id'    => $project->software_request_id,
                'development_project_id' => $project->id,
                'user_id'                => auth()->id(),
                'action_type'            => 'UAT_REVIEW',
                'old_value'              => $oldStatus->value,
                'new_value'              => $project->project_status->value,
                'reason'                 => "Review UAT oleh User Pemohon. Hasil: " . strtoupper($request->uat_status) . ". Catatan: " . ($request->uat_feedback ?? '-'),
            ]);
        });

        return redirect()->route('projects.show', $project->id)->with('success', 'Feedback UAT berhasil disimpan dan dikunci ke log sistem.');
    }

    public function closeProject($id)
    {
        $project = DevelopmentProject::findOrFail($id);
        $oldStatus = $project->project_status;

        DB::transaction(function () use ($project, $oldStatus) {
            $project->update([
                'project_status' => ProjectStatus::CLOSED,
                'is_active_load' => false,
                'closed_at'      => now(),
            ]);

            ProjectHistoryLog::create([
                'software_request_id'    => $project->software_request_id,
                'development_project_id' => $project->id,
                'user_id'                => auth()->id(),
                'action_type'            => 'PROJECT_CLOSED',
                'old_value'              => $oldStatus->value,
                'new_value'              => ProjectStatus::CLOSED->value,
                'reason'                 => "Aplikasi telah berhasil dideploy di server production dan diserahterimakan ke unit kerja. Proyek resmi ditutup.",
            ]);
        });

        return redirect()->route('projects.show', $project->id)->with('success', 'Luar biasa! Proyek resmi ditutup, arsip dikunci, dan kapasitas beban kerja programmer telah dibebaskan.');
    }

    public function storeRating(Request $request, $id)
    {
        $project = DevelopmentProject::findOrFail($id);

        if (auth()->id() !== $project->softwareRequest->user_id) {
            abort(403, 'Hanya user pengaju tiket yang sah yang dapat memberikan rating.');
        }

        $request->validate([
            'rating'          => 'required|integer|between:1,5',
            'rating_feedback' => 'nullable|string|max:500',
        ]);

        $project->softwareRequest->update([
            'rating'          => $request->rating,
            'rating_feedback' => $request->rating_feedback,
        ]);

        return redirect()->back()->with('success', 'Terima kasih banyak atas feedback rating yang Anda berikan!');
    }
}
