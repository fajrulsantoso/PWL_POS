<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class t_penjualan_detailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_penjualan_detail')->insert([
            [
                'barang_id' => 1,
                'penjualan_id' => 4, // Sesuaikan dengan yang ada di t_penjualan
                'harga' => 50000,
                'jumlah' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 2,
                'penjualan_id' => 5, // Sesuaikan dengan yang ada di t_penjualan
                'harga' => 75000,
                'jumlah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 3,
                'penjualan_id' => 6, // Sesuaikan dengan yang ada di t_penjualan
                'harga' => 100000,
                'jumlah' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
