<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\GoogleOauthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Auth::routes(['verify' => true]);
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/', [AuthController::class, 'login']);
// Route::post('/login', [AuthController::class, 'loginJson']);
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'storeRegister']);
Route::get('/forgot-password', [AuthController::class, 'forget']);
Route::post('/forgot-verify', [AuthController::class, 'forgetVerify']);

Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/verify-email/{email}', [AuthController::class, 'verifyEmail']);
Route::get('/failed-verification', [AuthController::class, 'failedVerification']);
Route::get('/pending-verification', [AuthController::class, 'pendingVerification']);
// Route::post('/pending-verification2', function() {
//     $email=session('email');
//     dd($email);
//     return view('pendingVerification', $email);
// })->name('pending');


Route::get('/get_transaksi', [TransaksiController::class, 'get_transaksi']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/email/verify/{id}/{hash}', 'AuthController@verifyEmail')->name('verification.verify');
    Route::get('/email/resend', 'AuthController@resendEmailVerification')->name('verification.resend');
  
});

// Untuk redirect ke Google
Route::get('login/google/redirect', [GoogleOauthController::class, 'signInRedirect'])
    ->middleware(['guest'])
    ->name('redirect');

//redirect google dari register.
Route::get('register/google/redirect', [GoogleOauthController::class, 'signUpRedirect'])
    ->middleware(['guest'])
    ->name('redirect');

// Untuk callback dari Google login
Route::get('login/google/callback', [GoogleOauthController::class, 'callback_login'])
    ->middleware(['guest'])
    ->name('callback_login');

// Untuk callback dari Google register
Route::get('register/google/callback', [GoogleOauthController::class, 'callback_register'])
->middleware(['guest'])
->name('callback_register');

    // Untuk logout
Route::post('logout', [GoogleOauthController::class, 'logout'])
->middleware(['auth'])
->name('logout');


Route::get('/dashboard', [AdminController::class, 'index']);

Route::middleware(['auth'])->group(
    function () {
        Route::get('/verify-success/{email}', [AuthController::class, 'verifySuccess']);

        // Route::get('/dashboard', [AdminController::class, 'index']);

        //Activity
        Route::get('/activity', [AdminController::class, 'history']);

        //Kerupuk
        Route::get('/master_product', [AdminController::class, 'kerupuk']);
        Route::post('/store_kerupuk', [AdminController::class, 'store']);
        Route::post('/add_barang', [AdminController::class, 'addBarang']);
        Route::put('/update_kerupuk', [AdminController::class, 'update']);
        Route::get('/kerupuk/delete/{id}', [AdminController::class, 'destroy']);

        //transaksi
        // Route::get('/get_transaksi', [TransaksiController::class, 'get_transaksi']);
        Route::get('/transaksi', [TransaksiController::class, 'transaksi']);
        Route::post('/store_transaksi', [TransaksiController::class, 'store_transaksi']);
        Route::put('/update_transaksi', [TransaksiController::class, 'update_transaksi']);

        // ====== vendor =========
        Route::get('/vendor', [AdminController::class, 'vendor']);
        Route::post('/store_vendor', [AdminController::class, 'addVendor']);
        Route::get('/vendor/delete/{id}', [AdminController::class, 'deleteVendor']);

        // ====== kategori ========
        Route::get('/kategori', [TransaksiController::class, 'kategori']);
        Route::post('/store_kategori', [TransaksiController::class, 'addKategori']);
        Route::get('/kategori/delete/{id}', [TransaksiController::class, 'deleteKategori']);

        //======= prod category =======
        Route::get('/prod_category', [ProductCategoryController::class, 'prod_kategori']);
        Route::post('/add_prod_kategori', [ProductCategoryController::class, 'addProdKategori']);

        //========repack ==========
        Route::get('/repack',[TransaksiController::class, 'getRepack']);
        Route::post('/add_repack',[TransaksiController::class, 'addRepack']);
    }
);