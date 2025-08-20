<?php
// database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'phone' => '+541234567890',
            'professional_url' => 'https://linkedin.com/in/admin',
            'photo_path' => 'photos/default-admin.jpg'
        ]);
    }
}