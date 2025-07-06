<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSuratTugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Jenis Kegiatan
        DB::table('kegiatans')->insert([
            ['nama' => 'Kuliah Lapangan'],
            ['nama' => 'Seminar'],
            ['nama' => 'Kerja Praktik'],
            ['nama' => 'Pelatihan'],
        ]);

        // Instansi
        DB::table('instansis')->insert([
            ['nama' => 'BRIN'],
            ['nama' => 'Kominfo'],
            ['nama' => 'Prodi TI'],
            ['nama' => 'Prodi Tambang'],
        ]);

        // Peran Tugas
        DB::table('perans')->insert([
            ['nama' => 'Narasumber'],
            ['nama' => 'Pendamping'],
            ['nama' => 'Peserta'],
            ['nama' => 'Moderator'],
            ['nama' => 'Panitia'],
            ['nama' => 'Lainnya'],
        ]);

        // Unit Kerja
        DB::table('units')->insert([
            ['id' => 1, 'nama' => 'Prodi TI'],
            ['id' => 2, 'nama' => 'Prodi Tambang'],
            ['id' => 3, 'nama' => 'Fakultas Teknik'],
            ['id' => 4, 'nama' => 'Fakultas Sains dan Teknologi'],
        ]);

        // Jabatan
        DB::table('jabatans')->insert([
            ['id' => 1, 'nama' => 'Ketua Prodi TI'],
            ['id' => 2, 'nama' => 'Ketua Prodi Tambang'],
            ['id' => 3, 'nama' => 'Ketua Fakultas Teknik'],
            ['id' => 4, 'nama' => 'Ketua Fakultas Sains dan Teknologi'],
        ]);

        // Pegawai Penugasan 
        DB::table('pegawai_penugasans')->insert([
            [
                'nama' => 'Dr. Andi Saputra',
                'nip' => '197812312003121001',
                'unit_id' => 2,
                'jabatan_id' => 2,
            ],
            [
                'nama' => 'Siti Marlina, M.T.',
                'nip' => '198504052010122001',
                'unit_id' => 1,
                'jabatan_id' => 1,
            ],
        ]);
    }
}
