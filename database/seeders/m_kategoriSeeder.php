<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Carbon;


class m_kategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_kategori')->insert([
            ['kategori_id' => 1, 'kategori_kode' => 'ELEC', 'kategori_nama' => 'Elektronik', 'created_at' => now(), 'updated_at' => now()],
            ['kategori_id' => 2, 'kategori_kode' => 'PAK', 'kategori_nama' => 'Pakaian', 'created_at' => now(), 'updated_at' => now()],
            ['kategori_id' => 3, 'kategori_kode' => 'MAK', 'kategori_nama' => 'Makanan', 'created_at' => now(), 'updated_at' => now()],
            ['kategori_id' => 4, 'kategori_kode' => 'MIN', 'kategori_nama' => 'Minuman', 'created_at' => now(), 'updated_at' => now()],
            ['kategori_id' => 5, 'kategori_kode' => 'PER', 'kategori_nama' => 'Perabotan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
