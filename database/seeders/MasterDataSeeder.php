<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');

        // 1. Seed Tabel Units (Unit Kerja Kampus)
        DB::table('units')->insert([
            ['id' => 1, 'name' => 'Fakultas Teknik & Ilmu Komputer', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Fakultas Kedokteran & Kesehatan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Direktorat Keuangan & Akuntansi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Direktorat Administrasi Akademik (DAA)', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Direktorat Sumber Daya Manusia (SDM)', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Badan Penjaminan Mutu Internal', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'UPT Teknologi Informasi dan Komunikasi (TIK)', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Seed Tabel Applications (Sistem Eksisting)
        DB::table('applications')->insert([
            ['id' => 1, 'name' => 'Sistem Informasi Akademik (SIAKAD)', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Sistem Informasi Keuangan Pegawai (KEU-PEG)', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Portal Penerimaan Mahasiswa Baru (PMB-ONLINE)', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Sistem Kepegawaian & Kinerja Dosen (HRIS)', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Seed Tabel Users (Aman & Spesifik untuk Testing)
        DB::table('users')->insert([
            // Role: Kepala TIK (Manajer Kelola)
            [
                'id' => 1,
                'username' => 'kepala.tik',
                'name' => 'Ir. Hermawan, M.T. (Kepala TIK)',
                'email' => 'kepala.tik@kampus.ac.id',
                'password' => $defaultPassword,
                'role' => 'kepala_tik',
                'unit_id' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Role: Programmer Pelaksana
            [
                'id' => 2,
                'username' => 'prog.budi',
                'name' => 'Budi Setiawan',
                'email' => 'prog.budi@kampus.ac.id',
                'password' => $defaultPassword,
                'role' => 'programmer',
                'unit_id' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'username' => 'prog.siti',
                'name' => 'Siti Aminah',
                'email' => 'prog.siti@kampus.ac.id',
                'password' => $defaultPassword,
                'role' => 'programmer',
                'unit_id' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Role: 5 User Pengusul dari Berbagai Unit Kerja
            [
                'id' => 4,
                'username' => 'user.akademik',
                'name' => 'Randi Wijaya (Akademik)',
                'email' => 'randi.w@kampus.ac.id',
                'password' => $defaultPassword,
                'role' => 'user',
                'unit_id' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'username' => 'user.keuangan',
                'name' => 'Diana Putri (Keuangan)',
                'email' => 'diana.p@kampus.ac.id',
                'password' => $defaultPassword,
                'role' => 'user',
                'unit_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'username' => 'user.sdm',
                'name' => 'Hendra Kurnia (SDM)',
                'email' => 'hendra.k@kampus.ac.id',
                'password' => $defaultPassword,
                'role' => 'user',
                'unit_id' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 7,
                'username' => 'user.teknik',
                'name' => 'Dr. Ahmad Fauzi (FTIK)',
                'email' => 'ahmad.f@kampus.ac.id',
                'password' => $defaultPassword,
                'role' => 'user',
                'unit_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 8,
                'username' => 'user.mutu',
                'name' => 'Siska Amelia (Mutu)',
                'email' => 'siska.a@kampus.ac.id',
                'password' => $defaultPassword,
                'role' => 'user',
                'unit_id' => 6,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 4. Seed Tabel Programmers (Kapasitas Maksimal Beban 20 Poin)
        DB::table('programmers')->insert([
            ['id' => 1, 'user_id' => 2, 'max_monthly_capacity' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'user_id' => 3, 'max_monthly_capacity' => 20, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
