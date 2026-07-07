<?php

namespace Database\Factories;

use App\Models\ProjectHistoryLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectHistoryLogFactory extends Factory
{
    protected $model = ProjectHistoryLog::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        $reasons = [
            'Rapat koordinasi menyepakati alokasi jadwal pengerjaan proyek.',
            'Pengerjaan proyek disetujui untuk dimulai oleh Kepala UPT TIK.',
            'Hasil pengujian UAT disetujui tanpa catatan perbaikan tambahan.',
            'Proses deployment ke server production selesai dilakukan secara terjadwal.',
            'Penyesuaian kebutuhan fitur darurat dari fakultas pengusul.',
        ];

        return [
            'software_request_id' => 1,
            'development_project_id' => null,
            'user_id' => 1, // Default to Kepala TIK
            'action_type' => $faker->randomElement(['PROJECT_ASSIGNED', 'UAT_APPROVED', 'DEPLOYED_TO_PRODUCTION']),
            'old_value' => null,
            'new_value' => null,
            'reason' => $faker->randomElement($reasons),
            'created_at' => $faker->dateTimeBetween('2026-01-01', '2026-08-31'),
            'updated_at' => now(),
        ];
    }
}
