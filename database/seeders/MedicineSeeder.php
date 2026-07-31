<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use App\Models\Supplier;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $sup1 = Supplier::where('code', 'SUP-001')->first();
        $sup2 = Supplier::where('code', 'SUP-002')->first();

        $medicines = [
            ['code' => 'OBT-001', 'name' => 'Paracetamol 500mg', 'category' => 'Tablet', 'unit' => 'Strip', 'purchase_price' => 3500, 'selling_price' => 6000, 'stock' => 150, 'min_stock' => 20, 'expired_date' => '2027-12-31'],
            ['code' => 'OBT-002', 'name' => 'Amoxicillin 500mg', 'category' => 'Kaplet', 'unit' => 'Strip', 'purchase_price' => 8000, 'selling_price' => 12000, 'stock' => 80, 'min_stock' => 15, 'expired_date' => '2027-08-30'],
            ['code' => 'OBT-003', 'name' => 'Cefadroxil 500mg', 'category' => 'Kapsul', 'unit' => 'Strip', 'purchase_price' => 12000, 'selling_price' => 18000, 'stock' => 45, 'min_stock' => 10, 'expired_date' => '2027-06-15'],
            ['code' => 'OBT-004', 'name' => 'OBH Sirup Batuk 100ml', 'category' => 'Sirup', 'unit' => 'Botol', 'purchase_price' => 15000, 'selling_price' => 22000, 'stock' => 30, 'min_stock' => 8, 'expired_date' => '2026-11-20'],
            ['code' => 'OBT-005', 'name' => 'Vitamin C 1000mg Effervescent', 'category' => 'Tablet', 'unit' => 'Tube', 'purchase_price' => 25000, 'selling_price' => 35000, 'stock' => 5, 'min_stock' => 10, 'expired_date' => '2027-05-10'], // Low stock warning!
            ['code' => 'OBT-006', 'name' => 'Amlodipine 10mg', 'category' => 'Tablet', 'unit' => 'Strip', 'purchase_price' => 5000, 'selling_price' => 8500, 'stock' => 90, 'min_stock' => 15, 'expired_date' => '2028-01-15'],
            ['code' => 'OBT-007', 'name' => 'Metformin 500mg', 'category' => 'Tablet', 'unit' => 'Strip', 'purchase_price' => 4000, 'selling_price' => 7000, 'stock' => 120, 'min_stock' => 20, 'expired_date' => '2027-10-10'],
        ];

        foreach ($medicines as $idx => $m) {
            $m['supplier_id'] = ($idx % 2 == 0) ? $sup1?->id : $sup2?->id;
            Medicine::updateOrCreate(['code' => $m['code']], $m);
        }
    }
}
