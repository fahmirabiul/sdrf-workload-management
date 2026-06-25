<?php

namespace Database\Factories;

use App\Models\DevelopmentProject;
use Illuminate\Database\Eloquent\Factories\Factory;

class DevelopmentProjectFactory extends Factory
{
    protected $model = DevelopmentProject::class;

    public function definition(): array
    {
        $tShirtSizes = [
            'S' => 2,
            'M' => 5,
            'L' => 10,
            'XL' => 20,
        ];

        $size = $this->faker->randomElement(array_keys($tShirtSizes));
        $projectStatus = $this->faker->randomElement(['waiting', 'in_development', 'suspended', 'uat_testing', 'ready_for_production', 'closed']);

        $startDate = $this->faker->dateTimeBetween('-20 days', 'now');
        $endDate = clone $startDate;
        // Deadline ditentukan dinamis maju beberapa hari ke depan
        $endDate->modify('+' . $this->faker->numberBetween(7, 30) . ' days');

        $feedbacks = [
            'Sistem sudah berjalan dengan baik, validasi QRIS sukses memotong saldo penguji.',
            'Tampilan tombol navigasi di layar mobile terpotong sedikit, mohon digeser 5 pixel ke kanan.',
            'Persetujuan cuti dosen sukses memicu email otomatis ke akun dekan fakultas.',
            'Aplikasi disetujui penuh tanpa ada catatan kekurangan setelah uji coba di server staging.',
        ];

        return [
            'software_request_id' => 1, // Akan otomatis ditimpa oleh TransactionDataSeeder
            'programmer_id' => $this->faker->randomElement([1, 2]), // Budi (1) atau Siti (2)
            't_shirt_size' => $size,
            'phase_title' => $size === 'XL' ? 'Pengembangan Arsitektur Inti Core Modul' : 'Pengerjaan Full Lifecycle',
            'story_points' => $tShirtSizes[$size],
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'project_status' => $projectStatus,
            'uat_status' => in_array($projectStatus, ['ready_for_production', 'closed']) ? 'approved' : ($projectStatus === 'uat_testing' ? 'pending' : null),
            'uat_feedback' => in_array($projectStatus, ['ready_for_production', 'closed']) ? $this->faker->randomElement($feedbacks) : null,
            'is_active_load' => !in_array($projectStatus, ['closed']),
            'closed_at' => $projectStatus === 'closed' ? now() : null,
        ];
    }
}
