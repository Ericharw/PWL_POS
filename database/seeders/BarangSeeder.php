<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        $barang = [
            ['Kode1', 'Obat', 10000, 15000],
            ['Kode2', 'Baju', 20000, 30000],
            ['Kode3', 'Buku', 5000, 8000],
            ['Kode4', 'Kipas Angin', 7000, 11000],
            ['Kode5', 'Minuman', 15000, 25000]
        ];

        for ($i = 1; $i <= 3; $i++) { // 3 Supplier
            foreach ($barang as $key => $b) {
                $data[] = [
                    'barang_id' => ($i - 1) * 5 + ($key + 1),
                    'kategori_id' => rand(1, 5),
                    'barang_kode' => $b[0] . $i,
                    'barang_nama' => $b[1],
                    'harga_beli' => $b[2],
                    'harga_jual' => $b[3],
                ];
            }
        }
        DB::table('m_barang')->insert($data);
    }
}
