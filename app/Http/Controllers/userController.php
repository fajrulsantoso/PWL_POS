<?php

namespace App\Http\Controllers;

use view;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    public function index()
    {
        // tambah data user dengan Eloquent Model
        $data = [
            'level_id' => '2',
            'username' => 'manager_tiga',
            'nama' => 'Manager 3',
            'password' => Hash::make('12345')
        ];

        UserModel::created($data); // update data user

        // coba akses model UserModel
        $user = UserModel::all(); // ambil semua data dari tabel m_user
        return view('user', ['data' => $user]);
    }
}
