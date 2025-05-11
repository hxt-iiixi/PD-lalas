<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Clear the products table before seeding
        DB::table('products')->delete();

        DB::table('products')->insert([
            [
                'name' => 'Paracetamol 500mg',
                'brand' => 'Biogesic',
                'selling_price' => 5.00,
                'stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ibuprofen 200mg',
                'brand' => 'Advil',
                'selling_price' => 7.50,
                'stock' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cough Syrup 100ml',
                'brand' => 'Tuseran',
                'selling_price' => 60.00,
                'stock' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
