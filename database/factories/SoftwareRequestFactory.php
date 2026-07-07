<?php

namespace Database\Factories;

use App\Models\SoftwareRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoftwareRequestFactory extends Factory
{
    protected $model = SoftwareRequest::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        // Kumpulan judul proyek riil kampus
        $titles = [
            'Integrasi SSO Google Workspace',
            'Sinkronisasi Feeder Neo-PDDIKTI',
            'Modul Pembayaran KKN via VA Mandiri',
            'Optimasi Query KHS SIAKAD',
            'Pembaruan Alur Validasi Cuti Dosen',
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
            'Penyelarasan data mahasiswa aktif dan lulusan secara berkala untuk keperluan sinkronisasi otomatis dengan server DIKTI.',
            'Penyediaan portal mandiri bagi unit kerja untuk melacak performa anggaran belanja secara langsung.',
        ];

        $impacts = [
            'Mempercepat sinkronisasi data dari 3 hari menjadi 5 menit otomatis',
            'Mencegah kebocoran manipulasi nilai mahasiswa oleh pihak luar',
            'Menghilangkan antrean fisik mahasiswa di biro keuangan',
            'Efisiensi waktu proses administrasi naik hingga 80%, menghilangkan potensi fraud data bayar, dan menaikkan tingkat kepuasan layanan mahasiswa.',
            'Mempersingkat birokrasi persetujuan dari 5 hari kerja menjadi hanya 1 jam pengerjaan secara sistem.',
            'Mencegah server down total yang dapat menghentikan aktivitas operasional akademik seluruh fakultas selama masa pengisian berkas krs.',
        ];

        $status = $faker->randomElement(['submitted', 'analysis_scheduled', 'approved', 'rejected']);

        return [
            'ticket_number' => 'REQ-2026-' . $faker->unique()->numberBetween(100, 999),
            // Acak pengguna pengaju dari ID 4 sampai 8 (5 user pengusul kita)
            'user_id' => $faker->numberBetween(4, 8),
            'unit_id' => $faker->numberBetween(1, 6),
            'application_id' => $faker->numberBetween(1, 4),
            'request_type' => $faker->randomElement(['new_feature', 'modification', 'bug_fix']),
            'title' => $faker->randomElement($titles),
            'description' => $faker->randomElement($descriptions),
            'business_impact' => $faker->randomElement($impacts),
            'target_used_date' => $faker->dateTimeBetween('+1 month', '+3 months')->format('Y-m-d'),
            'attachment_path' => null,
            'status' => $status,
            'meeting_notes' => $status !== 'submitted' ? 'Hasil rapat pleno disetujui untuk dilaksanakan dengan prioritas sedang demi kelancaran operasional.' : null,
            'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }
}
