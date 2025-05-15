<?php

use App\Http\Controllers\Customer\Auth\LoginController;
use App\Http\Controllers\Customer\Auth\RegisterController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;


Route::prefix('customer')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('customer.home');
    Route::get('products', [CustomerController::class, 'viewProducts'])->name('customer.products');
    Route::get('about', [CustomerController::class, 'aboutUs'])->name('customer.about');
    // Route::get('about-us', [CustomerController::class, 'about'])->name('about');
    
    // Contact
    // Route::get('contact', [CustomerController::class, 'index'])->name('contact');
    // Route::post('contact', [CustomerController::class, 'send']);

});

Route::prefix('customer')->middleware('guest:customer')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('customer.login');
    Route::post('login', [LoginController::class, 'login'])->name('customer.login.submit');
    
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('customer.register');
    Route::post('register', [RegisterController::class, 'register'])->name('customer.register.submit');
});

Route::prefix('customer')->middleware('auth:customer', 'PreventBackHistory')->group(function () {
    // Dashboard
    Route::get('dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    
    // Profile
    Route::get('profile', [CustomerController::class, 'viewProfile'])->name('profile');
    Route::put('profile-update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    Route::put('reset-password', [CustomerController::class, 'updatePassword'])->name('customer.updatePass');
    
    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('customer.logout');

    Route::get('auth/products', [CustomerController::class, 'authProducts'])->name('customer.auth.products');
    Route::post('auth/reserve-product', [CustomerController::class, 'reserveProduct'])->name('customer.reserve.product');
    Route::delete('auth/delete-reserve/{id}', [CustomerController::class, 'cancelReservation'])->name('customer.delete.reserve');

    Route::get('auth/about', [CustomerController::class, 'authAboutUs'])->name('customer.auth.about');

});
