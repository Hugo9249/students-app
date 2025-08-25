<?php
// database/seeders/AdminUserSeeder.php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'], // condición única
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'is_admin' => 1,
                'phone' => '+541234567890',
                'professional_url' => 'https://linkedin.com/in/admin',
                'photo_path' => 'photos/default-admin.jpg',
            ]
        );
    }
}
