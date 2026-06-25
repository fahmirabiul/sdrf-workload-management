<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoftwareRequest;
use App\Models\DevelopmentProject;
use App\Models\ProjectHistoryLog;
use App\Enums\RequestStatus;
use App\Enums\ProjectStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data transaksi lama agar tidak bentrok saat di-seed ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DevelopmentProject::truncate();
        SoftwareRequest::truncate();
        ProjectHistoryLog::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        // =========================================================================
        // KASUS 1: REQUEST BARU MASUK (STATUS: SUBMITTED)
        // =========================================================================
        SoftwareRequest::create([
            'ticket_number' => 'SDRF-2026-0001',
            'user_id' => 5, // Hendra Kurnia (SDM)
            'unit_id' => 5,
            'application_id' => 3, // Portal Kepegawaian
            'request_type' => 'new_feature',
            'title' => 'Pengembangan Modul Pengajuan Cuti Tahunan Berbasis Mobile',
            'description' => 'Mengintegrasikan pengajuan cuti dosen dan tendik ke dalam aplikasi mobile.',
            'business_impact' => 'Mempercepat birokrasi persetujuan cuti dari 3 hari menjadi 5 menit.',
            'target_used_date' => $now->copy()->addMonths(2)->format('Y-m-d'),
            'status' => RequestStatus::SUBMITTED->value,
        ]);


        // =========================================================================
        // KASUS 2: PROYEK AKTIF YANG SEDANG DIKERJAKAN (STATUS: IN_DEVELOPMENT)
        // =========================================================================
        $req2 = SoftwareRequest::create([
            'ticket_number' => 'SDRF-2026-0002',
            'user_id' => 7, // Ahmad Fauzi (FTIK)
            'unit_id' => 1,
            'application_id' => 1, // SIAKAD
            'request_type' => 'modification',
            'title' => 'Integrasi Kuesioner Evaluasi Dosen pada Syarat Cetak KPU',
            'description' => 'Mewajibkan mahasiswa mengisi kuesioner evaluasi sebelum mengunduh Kartu Peserta Ujian.',
            'business_impact' => 'Menjamin tingkat partisipasi pengisian EDOM mencapai 100% demi syarat akreditasi.',
            'target_used_date' => $now->copy()->addWeeks(3)->format('Y-m-d'),
            'status' => RequestStatus::APPROVED->value,
            'meeting_notes' => 'Catatan Pengesahan: Proyek disetujui dalam rapat pleno. Diberikan bobot Medium.',
        ]);

        DevelopmentProject::create([
            'software_request_id' => $req2->id,
            'programmer_id' => 2, // Ditugaskan ke Programmer 2
            't_shirt_size' => 'M',
            'phase_title' => $req2->title,
            'story_points' => 5,
            'start_date' => $now->copy()->startOfMonth()->format('Y-m-d'),
            'end_date' => $now->copy()->startOfMonth()->addDays(12)->format('Y-m-d'),
            'project_status' => ProjectStatus::IN_DEVELOPMENT->value,
            'uat_status' => 'pending',
            'is_active_load' => true,
        ]);


        // =========================================================================
        // KASUS 3: PROYEK SIMULASI MANAJEMEN KONFLIK (PENANGGUHAN / SUSPEND)
        // Menampilkan 1 proyek yang di-suspend, dan otomatis melahirkan 1 proyek sisa
        // =========================================================================

        $reqConflict = SoftwareRequest::create([
            'ticket_number' => 'SDRF-2026-0003',
            'user_id' => 8, // Siska Amelia (Mutu)
            'unit_id' => 6,
            'application_id' => 1, // SIAKAD
            'request_type' => 'new_feature',
            'title' => 'Modul Tracer Study Berbasis Sinkronisasi API Forlap Dikti',
            'description' => 'Sistem pelacakan alumni institusi yang datanya wajib sinkron dengan kemendikbud.',
            'business_impact' => 'Pilar utama penilaian instrumen kriteria akreditasi perguruan tinggi.',
            'target_used_date' => $now->copy()->addMonths(2)->format('Y-m-d'),
            'status' => RequestStatus::APPROVED->value,
            'meeting_notes' => 'Catatan Pengesahan: Ukuran asli L (10 Pts). Ditangguhkan di tengah jalan karena adanya urgensi sistem lain.',
        ]);

        // A. Proyek Induk yang berstatus CLOSE_SUSPENDED
        $projectOld = DevelopmentProject::create([
            'software_request_id' => $reqConflict->id,
            'programmer_id' => 1, // Programmer 1
            't_shirt_size' => 'L',
            'phase_title' => $reqConflict->title,
            'story_points' => 10,
            'start_date' => $now->copy()->subWeeks(2)->format('Y-m-d'),
            'end_date' => $now->copy()->addDays(2)->format('Y-m-d'),
            'project_status' => ProjectStatus::CLOSE_SUSPENDED->value,
            'uat_status' => 'pending',
            'is_active_load' => false, // Beban sudah tidak aktif
        ]);

        // Rekam Jejak Log Pertama: Pencatatan Penangguhan Proyek Induk
        ProjectHistoryLog::create([
            'software_request_id' => $reqConflict->id,
            'development_project_id' => $projectOld->id,
            'user_id' => 1, // Admin Kepala TIK
            'action_type' => 'CLOSE_SUSPENDED',
            'old_value' => 'ProjectStatus::IN_DEVELOPMENT',
            'new_value' => 'ProjectStatus::CLOSE_SUSPENDED',
            'reason' => 'Proyek ditangguhkan darurat oleh Kepala TIK karena penugasan penanganan bug krusial sistem pembayaran. Sisa beban dialihkan ke proyek kloning sisa.',
        ]);

        // B. Proyek Kloning Pecahan (Sisa Beban Kerja) yang masuk ke antrean WAITING
        $projectPiece = DevelopmentProject::create([
            'software_request_id' => $reqConflict->id,
            'programmer_id' => 1,
            't_shirt_size' => 'L',
            'phase_title' => '[SISA SUSPEND: 4 Pts] - Modul Tracer Study Berbasis Sinkronisasi API Forlap Dikti',
            'story_points' => 4, // Sisa poin hasil perhitungan manual/sistem
            'start_date' => null,
            'end_date' => null,
            'project_status' => ProjectStatus::WAITING->value,
            'uat_status' => 'pending',
            'is_active_load' => true, // Menjadi beban tunggu aktif
        ]);

        // Rekam Jejak Log Kedua: Pencatatan Kelahiran Proyek Pecahan
        ProjectHistoryLog::create([
            'software_request_id' => $reqConflict->id,
            'development_project_id' => $projectPiece->id,
            'user_id' => 1, // Admin Kepala TIK
            'action_type' => 'SUSPEND_CLONED',
            'old_value' => 'ID Proyek Induk: ' . $projectOld->id,
            'new_value' => 'Proyek Kloning Sisa Tercipta',
            'reason' => 'Sistem melahirkan proyek baru pecahan sisa pekerjaan sebesar 4 Story Points dalam antrean WAITING tanpa batasan durasi ketat.',
        ]);


        // =========================================================================
        // KASUS 4: PROYEK SELESAI TOTAL (STATUS: CLOSED)
        // =========================================================================
        $req4 = SoftwareRequest::create([
            'ticket_number' => 'SDRF-2026-0004',
            'user_id' => 6, // Keuangan
            'unit_id' => 3,
            'application_id' => 2, // Keuangan
            'request_type' => 'bug_fix',
            'title' => 'Perbaikan Sinkronisasi Virtual Account Pembayaran UKT',
            'description' => 'Memperbaiki kegagalan callback otomatis yang menyebabkan status lunas mahasiswa tidak terupdate.',
            'business_impact' => 'Menghindari komplain massal mahasiswa saat masa registrasi semester baru.',
            'target_used_date' => $now->copy()->subDays(5)->format('Y-m-d'),
            'status' => RequestStatus::APPROVED->value,
            'meeting_notes' => 'Catatan Pengesahan: Bug fatal. Ditugaskan dan diselesaikan secepatnya.',
        ]);

        $projectClosed = DevelopmentProject::create([
            'software_request_id' => $req4->id,
            'programmer_id' => 1,
            't_shirt_size' => 'S',
            'phase_title' => $req4->title,
            'story_points' => 2,
            'start_date' => $now->copy()->subDays(10)->format('Y-m-d'),
            'end_date' => $now->copy()->subDays(7)->format('Y-m-d'),
            'project_status' => ProjectStatus::CLOSED->value,
            'uat_status' => 'approved',
            'is_active_load' => false,
            'closed_at' => $now->copy()->subDays(7),
        ]);

        // Rekam Jejak Log Ketiga: Penutupan Tugas Selesai
        ProjectHistoryLog::create([
            'software_request_id' => $req4->id,
            'development_project_id' => $projectClosed->id,
            'user_id' => 1,
            'action_type' => 'CLOSED',
            'old_value' => 'ProjectStatus::READY_FOR_PRODUCTION',
            'new_value' => 'ProjectStatus::CLOSED',
            'reason' => 'Aplikasi telah berhasil dideploy di server production dan diserahterimakan ke unit kerja. Proyek resmi ditutup.',
        ]);
    }
}
