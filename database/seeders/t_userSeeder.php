<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Tambahkan baris ini

class t_userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'level_id' => 1,
                'username' => 'admin',
                'nama' => 'Administrator',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 2,
                'level_id' => 2,
                'username' => 'manager',
                'nama' => 'Manager',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 3, // Perbaiki duplikat dan sesuaikan tipe data
                'level_id' => 3,
                'username' => 'staff',
                'nama' => 'Staff/Kasir', // Perbaiki nama, bukan user_id
                'password' => Hash::make('12345'),
            ],
        ];
        DB::table('t_user')->insert($data);
    }
}