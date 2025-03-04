<?php

use App\Http\Controllers\kategoriController;
use App\Http\Controllers\levelContoroller;
use App\Http\Controllers\userController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/level',[levelContoroller::class,'index']);
Route::get('/kategori',[kategoriController::class,'index']);
Route::get('/user',[userController::class,'index']);
Route::get('/user/tambah', [UserController::class,'tambah']);
Route::get('/user/tambah',[UserController::class, 'tambah']);
Route::get('/user/tambah_simpan',[UserController::class, 'tambah_simpan']);
Route::get('user/ubah/{id}', [UserController::class, 'ubah']);
Route::put('/user/tambah_simpan',[UserController::class, 'tambah_simpan']);
Route::get('/user/hapus/{id}',[UserController::class, 'hapus']);
