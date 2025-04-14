<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    // Proses login
    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }

        return redirect('login');
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    // Tampilkan halaman registrasi
    public function register()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('auth.register', ['level' => $level]);
        
    }

    // Proses registrasi
    public function postregister(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'msgField' => $validator->errors(),
                ]);
            }

            User::create($request->all());

            return Response::json([
                'status' => true,
                'message' => 'Data pengguna berhasil disimpan.',
                'redirect' => url('/login')
            ]);
        }

        return Response::json([
            'status' => false,
            'message' => 'Format permintaan tidak valid!'
        ], 400);
    }
}
