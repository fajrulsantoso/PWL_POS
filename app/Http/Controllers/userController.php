<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Mengambil data semua pengguna dengan level_id = 2
        $users = UserModel::where('level_id', 2)->get();
        
        // Menghitung jumlah pengguna dengan level_id = 2
        $jumlah = $users->count();

        // Mengirim data pengguna dan jumlahnya ke view
        return view('user', ['users' => $users, 'jumlah' => $jumlah]);
    }
}
