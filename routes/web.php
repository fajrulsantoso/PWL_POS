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

Route::get('/welcome', function () {
    return view('welcome');
});


Route::get('/level', [levelContoroller::class, 'index']);
Route::get('/kategori', [kategoriController::class, 'index']);
Route::get('/user', [userController::class, 'index']);
Route::get('/user/tambah', [UserController::class, 'tambah']);
Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);  // Ganti GET jadi POST
Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
Route::post('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);  // Tambah POST untuk ubah_simpan
Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);
