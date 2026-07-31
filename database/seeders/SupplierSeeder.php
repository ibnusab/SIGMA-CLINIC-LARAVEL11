<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['code' => 'SUP-001', 'name' => 'PT Kimia Farma Trading', 'phone' => '021-5551234', 'email' => 'order@kimiafarma.co.id', 'address' => 'Jl. Veteran No. 9, Jakarta'],
            ['code' => 'SUP-002', 'name' => 'PT Kalbe Farma Tbk', 'phone' => '021-4287388', 'email' => 'supply@kalbe.co.id', 'address' => 'Kawasan Industri Pulogadung, Jakarta'],
            ['code' => 'SUP-003', 'name' => 'PT Sanbe Farma', 'phone' => '022-6031234', 'email' => 'sales@sanbe-farma.com', 'address' => 'Jl. Industri No. 8, Bandung'],
        ];

        foreach ($suppliers as $s) {
            Supplier::updateOrCreate(['code' => $s['code']], $s);
        }
    }
}
