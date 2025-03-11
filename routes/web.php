<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*s
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);          // menampilkan halaman awal user 
    Route::post('/list', [UserController::class, 'list']);      // menampilkan data user dalam bentuk json untuk datatables 
    Route::get('/create', [UserController::class, 'create']);   // menampilkan halaman form tambah user 
    Route::post('/', [UserController::class, 'store']);         // menyimpan data user baru 
    Route::get('/{id}', [UserController::class, 'show']);       // menampilkan detail user 
    Route::get('/{id}/edit', [UserController::class, 'edit']);  // menampilkan halaman form edit user 
    Route::put('/{id}', [UserController::class, 'update']);     // menyimpan perubahan data user 
    Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
});



