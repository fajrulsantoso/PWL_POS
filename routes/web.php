<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\kategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WelcomeController;

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
  
    Route::get('/create_ajax', [UserController::class, 'create_ajax']);   // menampilkan halaman form tambah user 
    Route::post('/ajax', [UserController::class, 'store_ajax']);         // menyimpan data user baru 

    Route::post('/', [UserController::class, 'store']);         // menyimpan data user baru 
    Route::get('/{id}', [UserController::class, 'show']);       // menampilkan detail user 
    Route::get('/{id}/edit', [UserController::class, 'edit']);  // menampilkan halaman form edit user 
    Route::put('/{id}', [UserController::class, 'update']);     // menyimpan perubahan data user 
    Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); //menampilkan halaman form edit user ajax
    Route::put('/{id}/update-ajax', [UserController::class, 'update_ajax']);

});




Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index']);          // menampilkan halaman awal level 
    Route::post('/list', [LevelController::class, 'list']);      // menampilkan data level dalam bentuk json untuk datatables 
    Route::get('/create', [LevelController::class, 'create']);   // menampilkan halaman form tambah level 
    Route::post('/', [LevelController::class, 'store']);         // menyimpan data level baru 
    Route::get('/{id}', [LevelController::class, 'show']);       // menampilkan detail level 
    Route::get('/{id}/edit', [LevelController::class, 'edit']);  // menampilkan halaman form edit level 
    Route::put('/{id}', [LevelController::class, 'update']);     // menyimpan perubahan data level 
    Route::delete('/{id}', [LevelController::class, 'destroy']); // menghapus data level
});

Route::group(['prefix' => 'barang'], function () {
    Route::get('/', [BarangController::class, 'index']);          // menampilkan halaman awal barang 
    Route::post('/list', [ BarangController::class, 'list']);      // menampilkan data barang dalam bentuk json untuk datatables 
    Route::get('/create', [ BarangController::class, 'create']);   // menampilkan halaman form tambah barang 
    Route::post('/', [ BarangController::class, 'store']);         // menyimpan data barang baru 
    Route::get('/{id}', [ BarangController::class, 'show']);       // menampilkan detail barang 
    Route::get('/{id}/edit', [ BarangController::class, 'edit']);  // menampilkan halaman form edit barang 
    Route::put('/{id}', [ BarangController::class, 'update']);     // menyimpan perubahan data barang 
    Route::delete('/{id}', [ BarangController::class, 'destroy']); // menghapus data barang
});

Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [kategoriController::class, 'index']);          // menampilkan halaman awal kategori 
    Route::post('/list', [kategoriController::class, 'list']);      // menampilkan data kategori dalam bentuk json untuk datatables 
    Route::get('/create', [kategoriController::class, 'create']);   // menampilkan halaman form tambah kategori 
    Route::post('/', [kategoriController::class, 'store']);         // menyimpan data kategori baru 
    Route::get('/{id}', [kategoriController::class, 'show']);       // menampilkan detail kategori 
    Route::get('/{id}/edit', [kategoriController::class, 'edit']);  // menampilkan halaman form edit kategori 
    Route::put('/{id}', [kategoriController::class, 'update']);     // menyimpan perubahan data kategori 
    Route::delete('/{id}', [kategoriController::class, 'destroy']); // menghapus data kategori
});

Route::group(['prefix' => 'supplier'], function () {
    Route::get('/', [SupplierController::class, 'index']);          // menampilkan halaman awal supplier 
    Route::post('/list', [SupplierController::class, 'list']);      // menampilkan data supplier dalam bentuk json untuk datatables 
    Route::get('/create', [SupplierController::class, 'create']);   // menampilkan halaman form tambah supplier 
    Route::post('/', [SupplierController::class, 'store']);         // menyimpan data supplier baru 
    Route::get('/{id}', [SupplierController::class, 'show']);       // menampilkan detail supplier 
    Route::get('/{id}/edit', [SupplierController::class, 'edit']);  // menampilkan halaman form edit supplier 
    Route::put('/{id}', [SupplierController::class, 'update']);     // menyimpan perubahan data supplier 
    Route::delete('/{id}', [SupplierController::class, 'destroy']); // menghapus data supplier
});
