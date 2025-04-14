<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\kategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use Monolog\Level;

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


Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter (id), maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
// Register
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postregister']);
Route::middleware(['auth'])->group(function () {


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

        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk hapus data user Ajax

    });




    Route::middleware(['authorize:ADM'])->prefix('level')->group(function () {

        Route::get('/import', [LevelController::class, 'import']);
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']);
        Route::get('/export-excel', [LevelController::class, 'export_excel']);
        Route::get('/export-pdf', [LevelController::class, 'export_pdf']);
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // Pindah ke atas sebelum /{id}
        Route::post('/ajax', [LevelController::class, 'store_ajax']);

        Route::get('/', [LevelController::class, 'index']);
        Route::post('/list', [LevelController::class, 'list']);
        Route::get('/create', [LevelController::class, 'create']);
        Route::post('/', [LevelController::class, 'store']);
        Route::get('/{id}', [LevelController::class, 'show']);
        Route::get('/{id}/edit', [LevelController::class, 'edit']);
        Route::put('/{id}', [LevelController::class, 'update']);
        Route::delete('/{id}', [LevelController::class, 'destroy']);

        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
        Route::put('/{id}/update-ajax', [LevelController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
    });


    Route::middleware(['authorize:ADM,MNG,STF'])->prefix('barang')->group(function () {
        Route::get('/impor', [BarangController::class, 'import']);
        Route::post('/impor-ajax', [BarangController::class, 'import_ajax']);
        Route::get('/export-excel', [BarangController::class, 'export_excel']);
        Route::get('/export-pdf', [BarangController::class, 'export_pdf']);

        Route::get('/create_ajax', [BarangController::class, 'create_ajax']);             // Menampilkan halaman form tambah barang AJAX
        Route::post('/ajax', [BarangController::class, 'store_ajax']);                    // Menampilkan halaman form tambah barang AJAX
        Route::get('/', [BarangController::class, 'index']);          // menampilkan halaman awal barang 
        Route::post('/list', [BarangController::class, 'list']);      // menampilkan data barang dalam bentuk json untuk datatables 
        Route::get('/create', [BarangController::class, 'create']);   // menampilkan halaman form tambah barang 
        Route::post('/', [BarangController::class, 'store']);         // menyimpan data barang baru 
        Route::get('/{id}', [BarangController::class, 'show']);       // menampilkan detail barang 
        Route::get('/{id}/edit', [BarangController::class, 'edit']);  // menampilkan halaman form edit barang 
        Route::put('/{id}', [BarangController::class, 'update']);     // menyimpan perubahan data barang 
        Route::delete('/{id}', [BarangController::class, 'destroy']); // menghapus data barang

        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); //menampilkan halaman form edit user ajax
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // Untuk hapus data user Ajax


    });

    Route::middleware(['authorize:ADM,MNG'])->prefix('kategori')->group(function () {
        Route::get('/import', [kategoriController::class, 'import']);
        Route::post('/import-ajax', [kategoriController::class, 'import_ajax']);
        Route::get('/export-excel', [kategoriController::class, 'export_excel']);
        Route::get('/export-pdf', [kategoriController::class, 'export_pdf']);
        Route::get('/create_ajax', [kategoriController::class, 'create_ajax']);       // Menampilkan halaman form tambah level AJAX
        Route::post('/ajax', [kategoriController::class, 'store_ajax']);              // Menampilkan halaman form tambah level AJAX
        Route::get('/', [kategoriController::class, 'index']);          // menampilkan halaman awal kategori 
        Route::post('/list', [kategoriController::class, 'list']);      // menampilkan data kategori dalam bentuk json untuk datatables 
        Route::get('/create', [kategoriController::class, 'create']);   // menampilkan halaman form tambah kategori 
        Route::post('/', [kategoriController::class, 'store']);         // menyimpan data kategori baru 
        Route::get('/{id}', [kategoriController::class, 'show']);       // menampilkan detail kategori 
        Route::get('/{id}/edit', [kategoriController::class, 'edit']);  // menampilkan halaman form edit kategori 
        Route::put('/{id}', [kategoriController::class, 'update']);     // menyimpan perubahan data kategori 

        Route::delete('/{id}', [kategoriController::class, 'destroy']); // menghapus data kategori


    });

    Route::middleware(['authorize:ADM,MNG'])->prefix('supplier')->group(function () {
        Route::get('/import', [SupplierController::class, 'import']);
        Route::post('/import-ajax', [SupplierController::class, 'import_ajax']);
        Route::get('/export-excel', [SupplierController::class, 'export_excel']);
        Route::get('/export-pdf', [SupplierController::class, 'export_pdf']);

        Route::get('/create-ajax', [SupplierController::class, 'create_ajax']);           // Menampilkan halaman form tambah supplier AJAX
        Route::post('/ajax', [SupplierController::class, 'store_ajax']);                  // Menampilkan halaman form tambah supplier AJA

        Route::get('/', [SupplierController::class, 'index']);          // menampilkan halaman awal supplier 
        Route::post('/list', [SupplierController::class, 'list']);      // menampilkan data supplier dalam bentuk json untuk datatables 
        Route::get('/create', [SupplierController::class, 'create']);   // menampilkan halaman form tambah supplier 
        Route::post('/', [SupplierController::class, 'store']);         // menyimpan data supplier baru 
        Route::get('/{id}', [SupplierController::class, 'show']);       // menampilkan detail supplier 
        Route::get('/{id}/edit', [SupplierController::class, 'edit']);  // menampilkan halaman form edit supplier 
        Route::put('/{id}', [SupplierController::class, 'update']);     // menyimpan perubahan data supplier 
        Route::delete('/{id}', [SupplierController::class, 'destroy']); // menghapus data supplier



        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);          // Menampilkan halaman form edit supplier AJAX
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);      // Menampilkan halaman form edit supplier AJAX
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);     // Untuk tampilkan form confirm delete supplier AJAX
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);   // Untuk hapus data supplier AJAX

    });
});
