<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('products')->delete();

        $now = Carbon::now();

        $products = [
            ['name' => 'Paracetamol 500mg', 'brand' => 'Biogesic', 'selling_price' => 5.00, 'stock' => 100],
            ['name' => 'Ibuprofen 200mg', 'brand' => 'Advil', 'selling_price' => 7.50, 'stock' => 50],
            ['name' => 'Cough Syrup 100ml', 'brand' => 'Tuseran', 'selling_price' => 60.00, 'stock' => 25],
            ['name' => 'Amoxicillin 500mg', 'brand' => 'Amoxil', 'selling_price' => 10.00, 'stock' => 60],
            ['name' => 'Cetirizine 10mg', 'brand' => 'Zyrtec', 'selling_price' => 12.00, 'stock' => 80],
            ['name' => 'Loperamide 2mg', 'brand' => 'Imodium', 'selling_price' => 8.00, 'stock' => 40],
            ['name' => 'Salbutamol Inhaler', 'brand' => 'Ventolin', 'selling_price' => 150.00, 'stock' => 20],
            ['name' => 'Mefenamic Acid 500mg', 'brand' => 'Ponstan', 'selling_price' => 6.00, 'stock' => 90],
            ['name' => 'Ascorbic Acid 500mg', 'brand' => 'Cecon', 'selling_price' => 4.00, 'stock' => 200],
            ['name' => 'Hydroxychloroquine', 'brand' => 'Plaquenil', 'selling_price' => 30.00, 'stock' => 15],
            ['name' => 'Metformin 500mg', 'brand' => 'Glucophage', 'selling_price' => 14.00, 'stock' => 100],
            ['name' => 'Amlodipine 5mg', 'brand' => 'Norvasc', 'selling_price' => 9.00, 'stock' => 120],
            ['name' => 'Losartan 50mg', 'brand' => 'Cozaar', 'selling_price' => 11.00, 'stock' => 110],
            ['name' => 'Omeprazole 20mg', 'brand' => 'Losec', 'selling_price' => 13.00, 'stock' => 60],
            ['name' => 'Simvastatin 20mg', 'brand' => 'Zocor', 'selling_price' => 10.00, 'stock' => 75],
            ['name' => 'Aspirin 80mg', 'brand' => 'Bayer', 'selling_price' => 5.00, 'stock' => 95],
            ['name' => 'Calcium + Vitamin D', 'brand' => 'Caltrate', 'selling_price' => 18.00, 'stock' => 50],
            ['name' => 'Multivitamins', 'brand' => 'Centrum', 'selling_price' => 20.00, 'stock' => 150],
            ['name' => 'Clopidogrel 75mg', 'brand' => 'Plavix', 'selling_price' => 22.00, 'stock' => 30],
            ['name' => 'Atorvastatin 10mg', 'brand' => 'Lipitor', 'selling_price' => 17.00, 'stock' => 70],
            ['name' => 'Erythromycin 500mg', 'brand' => 'Ilosone', 'selling_price' => 16.00, 'stock' => 40],
            ['name' => 'Doxycycline 100mg', 'brand' => 'Doxicon', 'selling_price' => 12.00, 'stock' => 65],
            ['name' => 'Metronidazole 500mg', 'brand' => 'Flagyl', 'selling_price' => 9.00, 'stock' => 85],
            ['name' => 'Hydrocortisone Cream', 'brand' => 'DermAid', 'selling_price' => 45.00, 'stock' => 35],
            ['name' => 'Betadine Solution', 'brand' => 'Betadine', 'selling_price' => 30.00, 'stock' => 40],
            ['name' => 'Antacid Suspension', 'brand' => 'Kremil-S', 'selling_price' => 25.00, 'stock' => 50],
            ['name' => 'Sodium Chloride 0.9%', 'brand' => 'IV Fluid', 'selling_price' => 55.00, 'stock' => 25],
            ['name' => 'Loratadine 10mg', 'brand' => 'Claritin', 'selling_price' => 10.00, 'stock' => 90],
            ['name' => 'Phenylephrine HCl', 'brand' => 'Neozep', 'selling_price' => 8.00, 'stock' => 70],
            ['name' => 'B Complex + Iron', 'brand' => 'Sangobion', 'selling_price' => 14.00, 'stock' => 45],
            ['name' => 'Zinc Sulfate', 'brand' => 'Solmux Advance', 'selling_price' => 6.00, 'stock' => 85],
            ['name' => 'Oral Rehydration Salts', 'brand' => 'Hydrite', 'selling_price' => 5.00, 'stock' => 100],
            ['name' => 'Tolfenamic Acid 200mg', 'brand' => 'Clotan', 'selling_price' => 10.00, 'stock' => 60],
            ['name' => 'Salicylic Acid Ointment', 'brand' => 'Duofilm', 'selling_price' => 70.00, 'stock' => 20],
            ['name' => 'Sodium Ascorbate 500mg', 'brand' => 'Fern-C', 'selling_price' => 7.00, 'stock' => 120],
            ['name' => 'Antihistamine Drops', 'brand' => 'Allerkid', 'selling_price' => 55.00, 'stock' => 25],
            ['name' => 'Silymarin Capsule', 'brand' => 'Liveraide', 'selling_price' => 23.00, 'stock' => 40],
            ['name' => 'Iron + Folic Acid', 'brand' => 'Obimin', 'selling_price' => 15.00, 'stock' => 80],
            ['name' => 'Charcoal Tablets', 'brand' => 'Kaopectate', 'selling_price' => 6.00, 'stock' => 90],
        ];

        foreach ($products as &$product) {
            $product['created_at'] = $now;
            $product['updated_at'] = $now;
        }

        DB::table('products')->insert($products);
    }
}
