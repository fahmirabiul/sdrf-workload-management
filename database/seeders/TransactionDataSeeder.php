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
        DB::table('project_comments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        // =========================================================================
        // PHASE A: EXPLICIT BASELINE SCENARIOS (DO NOT ALTER)
        // =========================================================================

        // 1. Programmer 1 (Budi Setiawan):
        // Must have:
        // - 1 large project in June 2026 (13 Pts) marked as close_suspended
        // - 1 critical bug fix project in June (8 Pts) marked as production (causing a total active load of 21 Pts in June)
        // - 1 cloned piece project (5 Pts) in July 2026 marked as waiting.

        $reqConflict = SoftwareRequest::create([
            'ticket_number' => 'SDRF-2026-0003',
            'user_id' => 8, // Siska Amelia (Mutu)
            'unit_id' => 6,
            'application_id' => 1, // SIAKAD
            'request_type' => 'new_feature',
            'title' => 'Modul Tracer Study Berbasis Sinkronisasi API Forlap Dikti',
            'description' => 'Sistem pelacakan alumni institusi yang datanya wajib sinkron dengan kemendikbud.',
            'business_impact' => 'Pilar utama penilaian instrumen kriteria akreditasi perguruan tinggi.',
            'target_used_date' => '2026-08-31',
            'status' => RequestStatus::APPROVED->value,
            'meeting_notes' => 'Catatan Pengesahan: Ukuran asli L (10 Pts). Ditangguhkan di tengah jalan karena adanya urgensi sistem lain.',
            'created_at' => '2026-05-15 08:00:00',
        ]);

        // A. Proyek Induk yang berstatus CLOSE_SUSPENDED (13 Pts, Juni 2026)
        $projectOld = DevelopmentProject::create([
            'software_request_id' => $reqConflict->id,
            'programmer_id' => 1, // Budi Setiawan
            't_shirt_size' => 'L',
            'phase_title' => $reqConflict->title,
            'story_points' => 13,
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-15',
            'project_status' => ProjectStatus::CLOSE_SUSPENDED->value,
            'uat_status' => 'pending',
            'is_active_load' => false,
            'created_at' => '2026-06-01 08:00:00',
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
            'created_at' => '2026-06-15 10:00:00',
        ]);

        // B. Proyek Kloning Pecahan (Sisa Beban Kerja) yang masuk ke antrean WAITING (5 Pts, Juli 2026)
        $projectPiece = DevelopmentProject::create([
            'software_request_id' => $reqConflict->id,
            'programmer_id' => 1,
            't_shirt_size' => 'L',
            'phase_title' => '[SISA SUSPEND: 5 Pts] - Modul Tracer Study Berbasis Sinkronisasi API Forlap Dikti',
            'story_points' => 5,
            'start_date' => null,
            'end_date' => null,
            'project_status' => ProjectStatus::WAITING->value,
            'uat_status' => 'pending',
            'is_active_load' => true,
            'created_at' => '2026-07-01 09:00:00',
        ]);

        // Rekam Jejak Log Kedua: Pencatatan Kelahiran Proyek Pecahan
        ProjectHistoryLog::create([
            'software_request_id' => $reqConflict->id,
            'development_project_id' => $projectPiece->id,
            'user_id' => 1, // Admin Kepala TIK
            'action_type' => 'SUSPEND_CLONED',
            'old_value' => 'ID Proyek Induk: ' . $projectOld->id,
            'new_value' => 'Proyek Kloning Sisa Tercipta',
            'reason' => 'Sistem melahirkan proyek baru pecahan sisa pekerjaan sebesar 5 Story Points dalam antrean WAITING tanpa batasan durasi ketat.',
            'created_at' => '2026-07-01 09:00:00',
        ]);

        // C. Proyek Bug Fix Kritis di Bulan Juni (8 Pts) berstatus PRODUCTION (menyebabkan total beban Juni = 21 Pts)
        $reqBug = SoftwareRequest::create([
            'ticket_number' => 'SDRF-2026-0005',
            'user_id' => 5, // Diana Putri (Keuangan)
            'unit_id' => 3,
            'application_id' => 2, // Keuangan
            'request_type' => 'bug_fix',
            'title' => 'Perbaikan Kebocoran Memori & Sinkronisasi Pembayaran UKT VA',
            'description' => 'Mengatasi overhead memory limit pada saat proses transaksi sinkronisasi VA Mandiri di akhir bulan.',
            'business_impact' => 'Menghindari kegagalan pencatatan pembayaran UKT mahasiswa.',
            'target_used_date' => '2026-06-30',
            'status' => RequestStatus::APPROVED->value,
            'created_at' => '2026-06-05 08:00:00',
        ]);

        $projectBug = DevelopmentProject::create([
            'software_request_id' => $reqBug->id,
            'programmer_id' => 1,
            't_shirt_size' => 'M',
            'phase_title' => $reqBug->title,
            'story_points' => 8,
            'start_date' => '2026-06-10',
            'end_date' => '2026-06-25',
            'project_status' => ProjectStatus::PRODUCTION->value,
            'uat_status' => 'approved',
            'is_active_load' => true,
            'created_at' => '2026-06-10 08:00:00',
        ]);

        // Log untuk proyek Bug Fix
        ProjectHistoryLog::create([
            'software_request_id' => $reqBug->id,
            'development_project_id' => $projectBug->id,
            'user_id' => 1,
            'action_type' => 'DEPLOYED_TO_PRODUCTION',
            'old_value' => 'ready_for_production',
            'new_value' => 'production',
            'reason' => 'Perbaikan bug kritis pembayaran UKT dideploy darurat ke production server.',
            'created_at' => '2026-06-25 14:00:00',
        ]);

        // 2. Programmer 2 (Siti Aminah):
        // Must have a stable project running from June to July 2026 (12 Pts) marked as in_development.
        $reqStable = SoftwareRequest::create([
            'ticket_number' => 'SDRF-2026-0006',
            'user_id' => 7, // Dr. Ahmad Fauzi (FTIK)
            'unit_id' => 1,
            'application_id' => 1, // SIAKAD
            'request_type' => 'modification',
            'title' => 'Integrasi Kuesioner Evaluasi Dosen pada Syarat Cetak KPU',
            'description' => 'Mewajibkan mahasiswa mengisi kuesioner evaluasi sebelum mengunduh Kartu Peserta Ujian.',
            'business_impact' => 'Menjamin tingkat partisipasi pengisian EDOM mencapai 100% demi syarat akreditasi.',
            'target_used_date' => '2026-07-31',
            'status' => RequestStatus::APPROVED->value,
            'meeting_notes' => 'Catatan Pengesahan: Proyek disetujui dalam rapat pleno. Diberikan bobot Medium.',
            'created_at' => '2026-05-20 08:00:00',
        ]);

        $projectStable = DevelopmentProject::create([
            'software_request_id' => $reqStable->id,
            'programmer_id' => 2, // Siti Aminah
            't_shirt_size' => 'L',
            'phase_title' => $reqStable->title,
            'story_points' => 12,
            'start_date' => '2026-06-01',
            'end_date' => '2026-07-31',
            'project_status' => ProjectStatus::IN_DEVELOPMENT->value,
            'uat_status' => 'pending',
            'is_active_load' => true,
            'created_at' => '2026-06-01 08:00:00',
        ]);

        // 3. Core Logs & Comments: Keep precise comments
        DB::table('project_comments')->insert([
            [
                'software_request_id' => $reqConflict->id,
                'user_id' => 1,
                'comment' => 'Harap prioritaskan modul API Neo-PDDIKTI terlebih dahulu sebelum lanjut ke integrasi tracer study.',
                'created_at' => '2026-06-02 11:00:00',
                'updated_at' => '2026-06-02 11:00:00',
            ],
            [
                'software_request_id' => $reqConflict->id,
                'user_id' => 8,
                'comment' => 'Baik Pak, data alumni sudah disiapkan oleh bagian Biro Kemahasiswaan.',
                'created_at' => '2026-06-02 11:30:00',
                'updated_at' => '2026-06-02 11:30:00',
            ]
        ]);


        // =========================================================================
        // PHASE B: HIGH-DENSITY FACTORY AUTOMATION
        // =========================================================================
        $faker = \Faker\Factory::create('id_ID');
        $additionalCount = rand(30, 40);

        for ($i = 0; $i < $additionalCount; $i++) {
            // Gunakan SoftwareRequestFactory yang teroptimasi
            $request = SoftwareRequest::factory()->create();

            // Jika status request disetujui, buat proyek pengembangan terkait
            if ($request->status === RequestStatus::APPROVED->value) {
                // Gunakan DevelopmentProjectFactory yang teroptimasi (tanggal tersebar Jan-Agt 2026)
                $project = DevelopmentProject::factory()->create([
                    'software_request_id' => $request->id,
                ]);

                $status = $project->project_status->value ?? $project->project_status;

                $logsToCreate = [];

                if (in_array($status, ['uat_testing', 'ready_for_production', 'production', 'closed'])) {
                    // PROJECT_ASSIGNED (waiting -> in_development)
                    $logsToCreate[] = [
                        'action_type' => 'PROJECT_ASSIGNED',
                        'old_value' => 'waiting',
                        'new_value' => 'in_development',
                        'reason' => 'Programmer dialokasikan untuk memulai tahap penulisan kode (coding).',
                        'created_at' => Carbon::parse($project->start_date)->addHours(1),
                    ];
                }

                if (in_array($status, ['ready_for_production', 'production', 'closed'])) {
                    // UAT_APPROVED (in_development -> ready_for_production)
                    $logsToCreate[] = [
                        'action_type' => 'UAT_APPROVED',
                        'old_value' => 'in_development',
                        'new_value' => 'ready_for_production',
                        'reason' => 'Pengguna menyetujui hasil UAT dan menandai sistem siap dideploy ke production.',
                        'created_at' => Carbon::parse($project->end_date)->subDays(2)->addHours(2),
                    ];
                }

                if (in_array($status, ['production', 'closed'])) {
                    // DEPLOYED_TO_PRODUCTION (ready_for_production -> production/closed)
                    $logsToCreate[] = [
                        'action_type' => 'DEPLOYED_TO_PRODUCTION',
                        'old_value' => 'ready_for_production',
                        'new_value' => $status,
                        'reason' => 'Proses deployment sistem ke server production utama telah sukses dilakukan.',
                        'created_at' => Carbon::parse($project->end_date)->addHours(5),
                    ];
                }

                foreach ($logsToCreate as $logData) {
                    ProjectHistoryLog::create(array_merge([
                        'software_request_id' => $request->id,
                        'development_project_id' => $project->id,
                        'user_id' => 1, // Kepala TIK
                        'updated_at' => now(),
                    ], $logData));
                }
            }
        }
    }
}
