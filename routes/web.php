<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
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

Route::pattern('id','[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

// Auth Routes
Route::get('login', [AuthController::class,'login'])->name('login');
Route::post('login', [AuthController::class,'postlogin']);
Route::get('logout', [AuthController::class,'logout'])->middleware('auth');

Route::get('register', [RegisterController::class, 'index']);
Route::post('register', [RegisterController::class, 'store']);

Route::middleware(['auth'])->group(function(){ // artinya semua route di dalam group ini harus

    // masukkan semua route yang perlu autentikasi di sini
    // Homepage
    Route::get('/', [WelcomeController::class, 'index']);

    // Hanya Admin (ADM)
    Route::middleware(['authorize:ADM'])->group(function () {
        Route::get('/user', [UserController::class, 'index']);
        Route::get('/user/list', [UserController::class, 'list']);
        Route::get('/user/create', [UserController::class, 'create']);
        Route::get('/user/create_ajax', [UserController::class, 'create_ajax']);
        Route::post('/user', [UserController::class, 'store']);
        Route::post('/user/ajax', [UserController::class, 'store_ajax']);
        Route::get('/user/{id}', [UserController::class, 'show']);
        Route::get('/user/{id}/show_ajax', [UserController::class, 'show_ajax']);
        Route::get('/user/{id}/edit', [UserController::class, 'edit']);
        Route::put('/user/{id}', [UserController::class, 'update']);
        Route::get('/user/{id}/edit_ajax', [UserController::class, 'edit_ajax']);
        Route::put('/user/{id}/update_ajax', [UserController::class, 'update_ajax']);
        Route::get('/user/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
        Route::delete('/user/{id}/delete_ajax', [UserController::class, 'delete_ajax']);
        Route::delete('/user/{id}', [UserController::class, 'destroy']);
    });
    
    Route::middleware(['authorize:ADM'])->group(function () {
        Route::get('/level', [LevelController::class, 'index']);
        Route::get('/level/list', [LevelController::class, 'list']);
        Route::get('/level/create', [LevelController::class, 'create']);
        Route::post('/level', [LevelController::class, 'store']);
        Route::get('/level/create_ajax', [LevelController::class, 'create_ajax']);
        Route::post('/level/ajax', [LevelController::class, 'store_ajax']);
        Route::get('/level/{id}/show_ajax', [LevelController::class, 'show_ajax']);
        Route::get('/level/{id}', [LevelController::class, 'show']);
        Route::get('/level/{id}/edit', [LevelController::class, 'edit']);
        Route::put('/level/{id}', [LevelController::class, 'update']);
        Route::get('/level/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
        Route::put('/level/{id}/update_ajax', [LevelController::class, 'update_ajax']);
        Route::get('/level/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
        Route::delete('/level/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
        Route::delete('/level/{id}', [LevelController::class, 'destroy']);
        Route::get('/level/import', [LevelController::class, 'import']);
        Route::post('/level/import_ajax', [LevelController::class, 'import_ajax']);
    });
    
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        Route::get('/kategori', [KategoriController::class, 'index']);
        Route::get('kategori/list', [KategoriController::class, 'list']);
        Route::get('kategori/create', [KategoriController::class, 'create']);
        Route::get('kategori/create_ajax', [KategoriController::class, 'create_ajax']);
        Route::post('/kategori', [KategoriController::class, 'store']);
        Route::post('kategori/ajax', [KategoriController::class, 'store_ajax']);
        Route::get('kategori/{id}', [KategoriController::class, 'show']);
        Route::get('kategori/{id}/show_ajax', [KategoriController::class, 'show_ajax']);
        Route::get('kategori/{id}/edit', [KategoriController::class, 'edit']);
        Route::put('kategori/{id}', [KategoriController::class, 'update']);
        Route::get('kategori/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
        Route::put('kategori/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
        Route::get('kategori/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
        Route::delete('kategori/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);
        Route::delete('kategori/{id}', [KategoriController::class, 'destroy']);
    });
    
    Route::middleware(['authorize:ADM,MNG'])->group(function () {
        Route::get('/supplier', [SupplierController::class, 'index']);
        Route::get('supplier/list', [SupplierController::class, 'list']);
        Route::get('supplier/create', [SupplierController::class, 'create']);
        Route::get('supplier/create_ajax', [SupplierController::class, 'create_ajax']);
        Route::post('/supplier', [SupplierController::class, 'store']);
        Route::post('supplier/ajax', [SupplierController::class, 'store_ajax']);
        Route::get('supplier/{id}', [SupplierController::class, 'show']);
        Route::get('supplier/{id}/show_ajax', [SupplierController::class, 'show_ajax']);
        Route::get('supplier/{id}/edit', [SupplierController::class, 'edit']);
        Route::put('suppllier/{id}', [SupplierController::class, 'update']);
        Route::get('supplier/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);
        Route::put('supplier/{id}/update_ajax', [SupplierController::class, 'update_ajax']);
        Route::get('supplier/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);
        Route::delete('supplier/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);
        Route::delete('supplier/{id}', [SupplierController::class, 'destroy']);
    });
    
    Route::group(['prefix' => 'barang'], function () {
        Route::get('/', [BarangController::class, 'index']);
        Route::get('/list', [BarangController::class, 'list']);
        Route::get('/create', [BarangController::class, 'create']);
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
        Route::post('/', [BarangController::class, 'store']);
        Route::post('/ajax', [BarangController::class, 'store_ajax']);
        Route::get('/{id}', [BarangController::class, 'show']);
        Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']);
        Route::get('/{id}/edit', [BarangController::class, 'edit']);
        Route::put('/{id}', [BarangController::class, 'update']);
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
        Route::delete('/{id}', [BarangController::class, 'destroy']);
        Route::get('/import', [BarangController::class, 'import']);
        Route::post('/import_ajax', [BarangController::class, 'import_ajax']);
    });
});