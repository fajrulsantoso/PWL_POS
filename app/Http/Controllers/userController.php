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
           'username' => 'manager11',
            'nama' => 'Manager11',
            'password' => Hash::make('12345'),
            'level_id' => 2,
        ]);
        $user->username = 'manager12';
        $user->save();
        $user->wasChanged(); // true
        $user->wasChanged('username'); // true
        $user->wasChanged('nama'); // false
        $user->wasChanged(['nama', 'username']); // true

        $user->wasChanged(); // false
        $user->wasChanged('username'); // false
        $user->wasChanged('nama'); // true
        dd($user->wasChanged(['nama', 'username'])); // false

        

     
        
        return view('user', ['data' => $user]);
    }
}
