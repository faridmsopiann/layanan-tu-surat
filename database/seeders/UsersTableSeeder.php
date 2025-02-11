<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Buat user admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        // Buat user pemohon
        $pemohon = User::create([
            'name' => 'Pemohon User',
            'email' => 'pemohon@example.com',
            'password' => Hash::make('password123'),
        ]);
        $pemohon->roles()->attach(Role::where('name', 'pemohon')->first());

        // Buat user TU
        $tu = User::create([
            'name' => 'TU User',
            'email' => 'tu@example.com',
            'password' => Hash::make('password123'),
        ]);
        $tu->roles()->attach(Role::where('name', 'tu')->first());

        // Buat user dekan
        $dekan = User::create([
            'name' => 'Dekan User',
            'email' => 'dekan@example.com',
            'password' => Hash::make('password123'),
        ]);
        $dekan->roles()->attach(Role::where('name', 'dekan')->first());

        // Buat user keuangan
        $keuangan = User::create([
            'name' => 'Keuangan User',
            'email' => 'keuangan@example.com',
            'password' => Hash::make('password123'),
        ]);
        $keuangan->roles()->attach(Role::where('name', 'keuangan')->first());

        // Buat user prodi
        $prodi = User::create([
            'name' => 'Prodi User',
            'email' => 'prodi@example.com',
            'password' => Hash::make('password123'),
        ]);
        $prodi->roles()->attach(Role::where('name', 'prodi')->first());
    }
}
