<?php
namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel; 
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth as Authentication;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): Factory|Redirector|RedirectResponse|View
    {
        if (Authentication::check()) return redirect('/');
        return view('auth.login');
    }

    public function postlogin(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');
            if (Authentication::attempt($credentials)) return response()->json(['status' => true, 'message' => 'Berhasil Masuk!', 'redirect' => url('/')]);
            return response()->json(['status' => false, 'message' => 'Proses masuk gagal!']);
        }

        return redirect('login');
    }

    public function register(): Redirector|RedirectResponse|View
    {
        if (Authentication::check()) return redirect('/');
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('auth.register', ['level' => $level]);
    }

    public function postregister(Request $request): JsonResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:t_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6'
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'message_field' => $validator->errors(),
                ]);
            }
    
            UserModel::create($request->all());
            return Response::json(['status' => true, 'message' => 'Data pengguna berhasil disimpan.', 'redirect' => url('/login')]);
        }
    
        return Response::json(['status' => false, 'message' => 'Format permintaan tidak valid!'], 400);
    }

    public function logout(Request $request): Redirector|RedirectResponse
    {
        Authentication::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}