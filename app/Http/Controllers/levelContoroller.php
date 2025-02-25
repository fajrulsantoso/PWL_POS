<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeUnit\FunctionUnit;

class levelContoroller extends Controller
{
    public function index()
    {
        // DB::insert('insert into m_level (level_kode, level_nama, created_at) values (?, ?, ?)', ['CUS', 'pelanggan', now()]);
        // return 'insert data baru berhasil';

        // $row = DB::update('UPDATE m_level SET level_nama = ? WHERE level_kode = ?', ['Customer', 'CUS']);
        // return 'Update data berhasil. Jumlah data yang diupdate: '.$row.' baris';

//         $row = DB::delete('delete from m_level where level_kode = ?', ['cus']);
// return 'Delete data berhasil. Jumlah data yang dihapus: ' . $row . ' baris';
$data = DB::select('select * from m_level');
return view('level', ['data' => $data]);
    }
}
