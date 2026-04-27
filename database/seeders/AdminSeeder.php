<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Library',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin',
                'points' => 999999,
                'status' => true,
            ]
        );
    }
}
