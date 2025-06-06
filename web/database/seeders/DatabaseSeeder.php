<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Safely clear users table for SQLite (use truncate for MySQL)
        DB::statement('DELETE FROM users');

        // Seed a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Call the product seeder
        $this->call([
            ProductSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
