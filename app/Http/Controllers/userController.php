<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = UserModel::firstOrCreate(
        [
            'username' => 'manager33',
            'nama' => 'Manager tiga tiga',
            'password' => Hash::make('12345'),
            'level_id' => 2
        ],
       );
       $user->save();
        return view('user', ['data' => $user]);
    }
}
