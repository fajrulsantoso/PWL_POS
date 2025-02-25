<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class m_supplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['supplier_id' => 1, 'supplier_kode' => 'SUP001', 'supplier_nama' => 'PT. Elektronik Maju', 'supplier_alamat' => 'Jl. Elektronik No. 1'],
            ['supplier_id' => 2, 'supplier_kode' => 'SUP002', 'supplier_nama' => 'CV. Pakaian Makmur', 'supplier_alamat' => 'Jl. Pakaian No. 2'],
            ['supplier_id' => 3, 'supplier_kode' => 'SUP003', 'supplier_nama' => 'UD. Makanan Lezat', 'supplier_alamat' => 'Jl. Makanan No. 3'],
        ];

        DB::table('m_supplier')->insert($data);

    }
}
