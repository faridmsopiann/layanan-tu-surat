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
        DB::table('jenis_kegiatan')->insert([
            ['nama' => 'Kuliah Lapangan'],
            ['nama' => 'Seminar'],
            ['nama' => 'Kerja Praktik'],
            ['nama' => 'Pelatihan'],
        ]);

        // Instansi
        DB::table('instansi')->insert([
            ['nama' => 'BRIN'],
            ['nama' => 'Kominfo'],
            ['nama' => 'Prodi TI'],
            ['nama' => 'Prodi Tambang'],
        ]);

        // Peran Tugas
        DB::table('peran_tugas')->insert([
            ['nama' => 'Narasumber'],
            ['nama' => 'Pendamping'],
            ['nama' => 'Peserta'],
            ['nama' => 'Moderator'],
            ['nama' => 'Panitia'],
            ['nama' => 'Lainnya'],
        ]);

        // Unit Kerja
        DB::table('unit_kerja')->insert([
            ['id' => 1, 'nama' => 'Prodi TI'],
            ['id' => 2, 'nama' => 'Prodi Tambang'],
            ['id' => 3, 'nama' => 'Fakultas Teknik'],
            ['id' => 4, 'nama' => 'Fakultas Sains dan Teknologi'],
        ]);

        // Dosen (Contoh)
        DB::table('dosen')->insert([
            [
                'nama' => 'Dr. Andi Saputra',
                'nip' => '197812312003121001',
                'unit_id' => 2,
            ],
            [
                'nama' => 'Siti Marlina, M.T.',
                'nip' => '198504052010122001',
                'unit_id' => 1,
            ],
        ]);
    }
}
