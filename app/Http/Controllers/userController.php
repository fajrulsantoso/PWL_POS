<?php

namespace App\Http\Controllers;

use id;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\UserModel;  // Pastikan model yang digunakan konsisten

class UserController extends Controller
{
    public function index()
    { 
        $user = UserModel::all();  // Mengambil semua data dari model UserModel
        return view('user', ['data' => $user]);
    }

    public function tambah()
    { 
        // Jika tidak ada data yang perlu dikirim, Anda bisa menghilangkan variabel $user
        return view('user_tambah'); // Atau jika ada data yang perlu ditambahkan, pastikan variabel $user diinisialisasi
    } 

    public function ubah($id) // Tambahkan parameter $id di sini
    {
        $user = UserModel::find($id); // Mencari user berdasarkan ID
        return view('user_ubah', ['data' => $user]); // Mengirim data user ke view
    }

    public function tambah_simpan(Request $request)
    {
        UserModel::create([
            'username' =>$request->username,
             'nama' =>$request->nama,
            'password'=>Hash::make($request->password),
             'level_id' => $request->level_id
        ]);
        return redirect('/user');
    }
     

     public function ubah_simpan($id,Request $request)
    {
        $user = UserModel::find($id);
        $user ->username = $request->username;
        $user ->nama = $request->nama;
        $user ->password = Hash::make('$request->password');
        $user->level_id = $request->level_id;
        $user->save();
        return redirect('/user');

    }
      public function hapus($id)
      {
        $user = UserModel::find($id);
        $user ->delete();
        return redirect('/user');
      }
      
}
