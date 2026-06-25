<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Enums\RequestStatus;
use App\Models\DevelopmentProject;
use App\Models\ProjectHistoryLog;
use App\Models\SoftwareRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Carbon\Carbon;

class DevelopmentProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = DevelopmentProject::with(['softwareRequest.user', 'softwareRequest.unit']);

        if ($user->role === 'programmer') {
            if ($user->programmer) {

                $projects = $query->where('programmer_id', $user->programmer->id)
                    ->orderBy('start_date', 'asc')
                    ->get();
            } else {
                $projects = collect();
            }
        } else {
            $projects = $query->latest()->get();
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

        // ambil log aktivitas
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

        // validasi input
        $request->validate([
            'status' => ['required', new Enum(ProjectStatus::class)],
        ]);

        $newStatus = ProjectStatus::tryFrom($request->status);
        if (!$newStatus) {
            return redirect()->back()->withErrors('Status yang dikirim tidak sah.');
        }

        // kunci alur kerja
        if ($oldStatus === ProjectStatus::WAITING && $newStatus !== ProjectStatus::IN_DEVELOPMENT) {
            return redirect()->back()->withErrors('Proyek di antrean harus masuk tahap In Development terlebih dahulu.');
        }

        if ($oldStatus === ProjectStatus::IN_DEVELOPMENT && $newStatus !== ProjectStatus::UAT_TESTING) {
            return redirect()->back()->withErrors('Proyek yang sedang dikoding hanya bisa digeser ke tahap UAT Testing.');
        }

        if (in_array($oldStatus, [ProjectStatus::SUSPENDED, ProjectStatus::CLOSE_SUSPENDED]) && $newStatus !== ProjectStatus::IN_DEVELOPMENT) {
            return redirect()->back()->withErrors('Proyek yang ditangguhkan hanya bisa dijalankan ulang ke tahap In Development.');
        }

        // eksekusi perubahan status proyek
        $project->update([
            'project_status' => $newStatus,
        ]);

        // sinkronisasi status request terkait
        $requestInduk = SoftwareRequest::find($project->software_request_id);
        if ($requestInduk) {
            $tiketStatus = match ($newStatus) {
                ProjectStatus::IN_DEVELOPMENT => 'approved',
                ProjectStatus::UAT_TESTING => 'analysis_scheduled',
                default => $requestInduk->status
            };
            $requestInduk->update(['status' => $tiketStatus]);
        }

        // catat log
        ProjectHistoryLog::create([
            'software_request_id'    => $project->software_request_id,
            'development_project_id' => $project->id,
            'user_id'                => auth()->id(),
            'action_type'            => 'PROJECT_STATE_TRANSITION',
            'old_value'              => $oldStatus->value,
            'new_value'              => $newStatus->value,
            'reason'                 => "Status proyek diperbarui menjadi: " . $newStatus->label(),
        ]);

        return redirect()->route('projects.show', $project->id)->with('success', 'Status proyek berhasil diperbarui ke tahap: ' . $newStatus->label());
    }

    public function submitUat(Request $request, $id)
    {
        // 1. validasi input
        $request->validate([
            'uat_status'   => 'required|in:approved,rejected',
            'uat_feedback' => 'required|string|min:10',
        ]);

        $project = DevelopmentProject::findOrFail($id);
        $oldStatus = $project->project_status;

        // Pastikan hanya User Pengaju Asli Tiket ini yang bisa mengisi UAT
        if (auth()->id() !== $project->softwareRequest->user_id) {
            abort(403, 'Hanya user pengaju tiket yang sah yang dapat mengisi umpan balik UAT.');
        }

        // 2. Tentukan Arah Alur Status Berdasarkan Hasil Pengujian User
        if ($request->uat_status === 'rejected') {
            $newProjectStatus = ProjectStatus::IN_DEVELOPMENT;
            $logActionType = 'PROJECT_UAT_REJECTED_&_REVISION_TRIGGERED';
            $flashMessage = 'Feedback UAT berhasil dikirim! Proyek dikembalikan ke status In Development untuk diperbaiki oleh programmer.';
        } else {
            $newProjectStatus = ProjectStatus::READY_FOR_PRODUCTION;
            $logActionType = 'PROJECT_UAT_APPROVED';
            $flashMessage = 'Selamat! Pengujian UAT berhasil disahkan. Proyek kini siap dirilis ke Production (Live).';
        }

        // 3. update data proyek
        $project->update([
            'project_status' => $newProjectStatus,
            'uat_status' => $request->uat_status,
            'uat_feedback' => $request->uat_feedback,
        ]);

        // 4. catat log
        ProjectHistoryLog::create([
            'software_request_id'    => $project->software_request_id,
            'development_project_id' => $project->id,
            'user_id'                => auth()->id(),
            'action_type'            => $logActionType,
            'old_value'              => $oldStatus->value,
            'new_value'              => $newProjectStatus->value,
            'reason'                 => "Hasil UAT: " . strtoupper($request->uat_status) . " | Catatan: " . $request->uat_feedback,
        ]);

        return redirect()->route('projects.show', $project->id)->with('success', $flashMessage);
    }

    public function closeProject($id)
    {
        // cek role kepala tik
        if (auth()->user()->role !== 'kepala_tik') {
            abort(403, 'Hanya Kepala TIK yang berwenang untuk menutup dan mengarsipkan proyek.');
        }

        $project = DevelopmentProject::findOrFail($id);
        $oldStatus = $project->project_status;

        // cek status uat
        if ($oldStatus !== ProjectStatus::READY_FOR_PRODUCTION) {
            return redirect()->back()->withErrors('Proyek hanya bisa ditutup jika sudah berstatus Ready for Production.');
        }

        // update data proyek
        $project->update([
            'project_status' => ProjectStatus::CLOSED,
            'is_active_load' => false,
            'closed_at' => Carbon::now(),
        ]);

        // catat log
        ProjectHistoryLog::create([
            'software_request_id'    => $project->software_request_id,
            'development_project_id' => $project->id,
            'user_id'                => auth()->id(),
            'action_type'            => 'PROJECT_CLOSED_&_ARCHIVED',
            'old_value'              => $oldStatus->value,
            'new_value'              => ProjectStatus::CLOSED->value,
            'reason'                 => "Aplikasi telah berhasil dideploy di server production dan diserahterimakan ke unit kerja. Proyek resmi ditutup.",
        ]);

        return redirect()->route('projects.show', $project->id)->with('success', 'Luar biasa! Proyek resmi ditutup, arsip dikunci, dan kapasitas beban kerja programmer telah dibebaskan');
    }

    public function storeRating(Request $request, $id)
    {
        $project = DevelopmentProject::findOrFail($id);

        // Proteksi keamanan: Pastikan yang memberi rating adalah user pengaju tiket itu sendiri
        if (auth()->id() !== $project->softwareRequest->user_id) {
            abort(403, 'Hanya user pengaju tiket yang sah yang dapat memberikan rating.');
        }

        // Validasi input bintang 1-5 dan feedback opsional
        $request->validate([
            'rating'          => 'required|integer|between:1,5',
            'rating_feedback' => 'nullable|string|max:500',
        ]);

        // Simpan data rating ke tabel software_requests melalui relasi proyek
        $project->softwareRequest->update([
            'rating'          => $request->rating,
            'rating_feedback' => $request->rating_feedback,
        ]);

        return redirect()->back()->with('success', 'Terima kasih banyak atas feedback rating yang Anda berikan! Layanan TIK akan terus kami tingkatkan.');
    }
}
