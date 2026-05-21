<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bansos.id'],
            [
                'name'     => 'Admin Kelurahan',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'superadmin@bansos.id'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('super123'),
                'role'     => 'superadmin',
            ]
        );
    }
}
