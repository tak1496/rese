<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\MailingController;

Route::prefix('/')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name("form.index");
    Route::post('/', [ShopController::class, 'index'])->name("form.index");
    Route::get('/like', [ShopController::class, 'like']);
    Route::get('/detail', [ShopController::class, 'detail'])->name('detail');
    Route::post('/reserve', [ShopController::class, 'reserve'])->middleware('auth')->name('reserve');
    //Route::get('/reserve', [ShopController::class, 'reserve_get'])->name('reserve_get');
    Route::get('/mypage', [ShopController::class, 'mypage'])->name('mypage');
    Route::get('/res_del', [ShopController::class, 'res_del']);
    Route::post('/res_change', [ShopController::class, 'res_change']);
    Route::post('/review', [ShopController::class, 'review']);
});

Route::prefix('/auth')->group(function () {
    Route::get('/login', [AuthController::class, 'check'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'checkUser'])->name('post.login');
    Route::get('/logout', [AuthController::class, 'getLogout']);
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('/thanks', [AuthController::class, 'thanks'])->name('thanks');
});

Route::prefix('/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::post('/register', [AdminController::class, 'register'])->name('admin.register');
    Route::get('/search', [AdminController::class, 'index'])->name('admin.search');
});

Route::prefix('/owner')->group(function () {
    Route::get('/', [OwnerController::class, 'index'])->name('owner');
    Route::post('/', [OwnerController::class, 'register'])->name('owner.register');
    Route::get('/check', [OwnerController::class, 'check']);
    Route::get('/situation', [OwnerController::class, 'situation']);
    Route::get('/qr', [OwnerController::class, 'qr'])->middleware('auth')->name('owner.qr');
    Route::get('/owner_reserve', [OwnerController::class, 'owner_reserve']);
});

    Route::get('/mail', [MailingController::class, 'index']);
    Route::post('/sendmail', [MailingController::class, 'sendMail']);

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';