<?php

namespace App\Http\Controllers;

use App\Models\UserModel;  // Sesuaikan jika modelnya memang UserModel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // public function index()
    // { 
    //     $user = UserModel::all();  // Ambil semua data user
    //     return view('user', ['data' => $user]);
    // }

    public function tambah()
    { 
        return view('user_tambah'); // Tampilkan form tambah
    } 

    public function tambah_simpan(Request $request)
    {
        // Validasi data (opsional)
        $request->validate([
            'username' => 'required',
            'nama' => 'required',
            'password' => 'required',
            'level_id' => 'required|integer',
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),  // Hash password
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil ditambahkan!');
    }

    public function ubah($id)
    {
        $user = UserModel::findOrFail($id);  // findOrFail untuk error handling
        return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan($id, Request $request)
    {
        $user = UserModel::findOrFail($id);  // findOrFail lebih aman

        // Validasi data (opsional)
        $request->validate([
            'username' => 'required',
            'nama' => 'required',
            'password' => 'required',
            'level_id' => 'required|integer',
        ]);

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password);  // Perbaikan di sini
        $user->level_id = $request->level_id;
        $user->save();

        return redirect('/user')->with('success', 'Data user berhasil diubah!');
    }

    public function hapus($id)
    {
        $user = UserModel::findOrFail($id);  // findOrFail untuk error handling
        $user->delete();
        return redirect('/user')->with('success', 'Data user berhasil dihapus!');
    }
    public function index()
    {
        $user = UserModel::with('level')->get();
        return view('user', ['data' => $user]);
    }

}
