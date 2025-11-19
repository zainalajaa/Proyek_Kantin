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
            'email' => 'admin@pln.com',       // username login
            'password' => bcrypt('password11'), // password HARUS di-hash
        ]);
    }
}
