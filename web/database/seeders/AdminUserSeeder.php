<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kdvenecia@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'), // You can change this securely later
                'is_admin' => true, // assumes you have an `is_admin` boolean field
                'is_active' => true, // if you have an `is_active` field
                'is_approved' => true, // if your schema includes this
            ]
        );
    }
}
