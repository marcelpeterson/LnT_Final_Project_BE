<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'handphone' => '081234567890',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Marcel',
            'email' => 'marcel@gmail.com',
            'password' => Hash::make('marcel123'),
            'handphone' => '081234567890',
        ]);
    }
}
