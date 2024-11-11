<?php

namespace Database\Seeders;

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
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Pemohon User',
            'email' => 'pemohon@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pemohon',
        ]);

        User::create([
            'name' => 'TU User',
            'email' => 'tu@example.com',
            'password' => Hash::make('password123'),
            'role' => 'tu',
        ]);

        User::create([
            'name' => 'Dekan User',
            'email' => 'dekan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'dekan',
        ]);

        User::create([
            'name' => 'Keuangan User',
            'email' => 'keuangan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'keuangan',
        ]);
    }
}
