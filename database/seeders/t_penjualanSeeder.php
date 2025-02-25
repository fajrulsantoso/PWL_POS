<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Tambahkan ini untuk membuat kode unik

class T_PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_penjualan')->insert([
            [
                'user_id' => 1, // Sesuaikan dengan user yang ada
                'pembeli' => 1,
                'penjualan_kode' => 'PJ-' . Str::random(6), // Buat kode unik
                'penjualan_tanggal' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2, // Sesuaikan dengan user yang ada
                'pembeli' => 2,
                'penjualan_kode' => 'PJ-' . Str::random(6),
                'penjualan_tanggal' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3, // Sesuaikan dengan user yang ada
                'pembeli' => 3,
                'penjualan_kode' => 'PJ-' . Str::random(6),
                'penjualan_tanggal' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
