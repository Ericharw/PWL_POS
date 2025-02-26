<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['supplier_id' => 1, 'supplier_nama' => 'Pertamina', 'alamat' => 'Jl. Raya No. 1 Surabaya', 'telepon' => '081234567890'],
            ['supplier_id' => 2, 'supplier_nama' => 'Indofood', 'alamat' => 'Jl. Melati No. 2 Pasuruan', 'telepon' => '081234567891'],
            ['supplier_id' => 3, 'supplier_nama' => 'Kimia Farma', 'alamat' => 'Jl. Mawar No. 3 Malang', 'telepon' => '081234567892'],
        ];
        DB::table('m_supplier')->insert($data);
    }
}
