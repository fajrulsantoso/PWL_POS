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
        // $user = UserModel::find0r(20, ['username','nama'], function () {
        //     abort(404);
        // });
        $user = UserModel::findOr(20, ['username', 'nama'], function () {
            abort(404);
        });

        // coba akses model UserModel
        // $user = UserModel::firstwhere('level_id', 1); // ambil semua data dari tabel m_user
        return view('user', ['data' => $user]);
    }
}
