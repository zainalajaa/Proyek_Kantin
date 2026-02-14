<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin; // pastikan ini sesuai namespace

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin Kantin',
            'email' => 'kantin01@gmail.com',       // username login
            'password' => bcrypt('password01'), // password HARUS di-hash
        ]);
    }
}
