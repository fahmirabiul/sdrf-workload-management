<?php

namespace Database\Factories;

use App\Models\SoftwareRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoftwareRequestFactory extends Factory
{
    protected $model = SoftwareRequest::class;

    public function definition(): array
    {
        // Kumpulan judul proyek riil kampus
        $titles = [
            'Modul Integrasi Pembayaran Tagihan UKT Mahasiswa via QRIS',
            'Pengembangan Fitur Pengajuan Cuti Dosen secara Online Terintegrasi HRIS',
            'Optimasi Database Sinkronisasi Pengisian KRS pada Kenaikan Traffic Portal SIAKAD',
            'Penambahan Fitur Unggah Berkas Persyaratan Kelulusan Yudisium di DAA',
            'Sistem Monitoring Dashboard Anggaran Per Unit Kerja Real-Time',
            'Perbaikan Bug Tampilan Nilai Transkrip Mahasiswa Tidak Muncul di Sisi Mobile',
            'Modul Validasi Otomatis Kelayakan Beasiswa Berbasis IPK dan Pendapatan Orang Tua',
            'Pengembangan Sistem Pelaporan Log Harian Pegawai & Kinerja Dosen Sesuai SISTER',
        ];

        $descriptions = [
            'Permintaan ini bertujuan untuk mempermudah transaksi administrasi keuangan agar tidak terjadi antrean manual di bank mitra saat masa registrasi semester baru.',
            'Diperlukan sistem digitalisasi terpusat agar pengajuan tidak lagi menggunakan kertas (paperless) dan persetujuan dekan bisa dilakukan via notifikasi email.',
            'Sistem mengalami perlambatan akut ketika diakses oleh lebih dari 5.000 mahasiswa secara bersamaan pada 10 menit pertama pengisian KRS dibongkar.',
            'Mengganti alur verifikasi berkas fisik yang rawan hilang menjadi penyimpanan cloud terenkripsi agar memudahkan tim auditor dalam mengevaluasi data kelulusan.',
        ];

        $impacts = [
            'Efisiensi waktu proses administrasi naik hingga 80%, menghilangkan potensi fraud data bayar, dan menaikkan tingkat kepuasan layanan mahasiswa.',
            'Mempersingkat birokrasi persetujuan dari 5 hari kerja menjadi hanya 1 jam pengerjaan secara sistem.',
            'Mencegah server down total yang dapat menghentikan aktivitas operasional akademik seluruh fakultas selama masa pengisian berkas krs.',
        ];

        $status = $this->faker->randomElement(['submitted', 'analysis_scheduled', 'approved', 'rejected']);

        return [
            'ticket_number' => 'REQ-2026-' . $this->faker->unique()->numberBetween(100, 999),
            // Acak pengguna pengaju dari ID 4 sampai 8 (5 user pengusul kita)
            'user_id' => $this->faker->numberBetween(4, 8),
            'unit_id' => $this->faker->numberBetween(1, 6),
            'application_id' => $this->faker->numberBetween(1, 4),
            'request_type' => $this->faker->randomElement(['new_feature', 'modification', 'bug_fix']),
            'title' => $this->faker->randomElement($titles),
            'description' => $this->faker->randomElement($descriptions),
            'business_impact' => $this->faker->randomElement($impacts),
            'target_used_date' => $this->faker->dateTimeBetween('+1 month', '+3 months')->format('Y-m-d'),
            'attachment_path' => null,
            'status' => $status,
            'meeting_notes' => $status !== 'submitted' ? 'Hasil rapat pleno disetujui untuk dilaksanakan dengan prioritas sedang demi kelancaran operasional.' : null,
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }
}
