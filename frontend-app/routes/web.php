<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
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

Route::get('/', fn () => redirect('/login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('frontend.login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('frontend.register');
    Route::post('/register', [AuthController::class, 'register'])->name('frontend.register.submit');
});

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('frontend.dashboard');
    Route::get('/user/edit-profile', [DashboardController::class, 'edit_profile'])->name('profile.edit');
    Route::get('/user/update-profile', [DashboardController::class, 'update_profile'])->name('user.update');

    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/user/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::get('/admin/user/view/{id}', [AdminController::class, 'view'])->name('admin.users.view');
    Route::get('/admin/user/delete/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');


Route::middleware('auth')->group(function () {
   
    Route::post('/logout', [AuthController::class, 'logout'])->name('frontend.logout');

});

Route::post('/store-token',[AuthController::class, 'store_token']);
