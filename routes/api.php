<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DeliveryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    // AUTH DRIVER SIDE
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/update-profile', [AuthController::class, 'updateProfile']);
    Route::put('/update-password', [AuthController::class, 'updatePassword']);

    Route::get('/index', [DeliveryController::class, 'index']); //home.tsx

    Route::get('/ongoingDeliveries', [DeliveryController::class, 'ongoingDelivery']); //deliveries.tsx
    Route::get('/completeStatus', [DeliveryController::class, 'completeStatus']); //deliveries.tsx
    Route::get('/pendingDeliveries', [DeliveryController::class, 'pendingDelivery']); //pending_deliveries.tsx
    Route::get('/historyDeliveries', [DeliveryController::class, 'historyDelivery']); //history_deliveries.tsx
    Route::post('/acceptDeliveries', [DeliveryController::class, 'acceptDelivery']); // Accept Delivery Function
    Route::post('/completeDeliveries', [DeliveryController::class, 'completeDelivery']); // Complete Delivery Function
    Route::get('/countPending', [DeliveryController::class, 'countPending']); // Count Pending Deliveries Function

    // AUTH CUSTOMER SIDE
    Route::get('/home', [CustomerController::class, 'home']); //home.tsx
    Route::get('/reserved', [CustomerController::class, 'getReservedProducts']); //activity.tsx
    Route::get('/history', [CustomerController::class, 'getHistory']); //activity.tsx
    Route::post('/reserved-products', [CustomerController::class, 'reserveProduct']); //Reserve Products Function
    Route::delete('/delete-reserved/{id}', [CustomerController::class, 'cancelReservation']); //Delete Reserved Products Function
    Route::get('/delivery', [CustomerController::class, 'getDelivery']); //delivery.tsx
    Route::get('/customerProfile', [CustomerController::class, 'getProfile']); //profile.tsx
    Route::post('/customerLogout', [CustomerController::class, 'logout']); //Logout Function
    Route::put('/updateCustomerProfile', [CustomerController::class, 'updateCustomerProfile']); //Update Profile Details Function
    Route::put('/updateCustomerPassword', [CustomerController::class, 'updateCustomerPassword']); //Change Password Function

});

// CUSTOMER SIDE
Route::get('/index', [CustomerController::class, 'index']); //index.tsx
Route::post('/loginCustomer', [AuthController::class, 'loginCustomer']); //login.tsx
Route::post('/registerCustomer', [AuthController::class, 'registerCustomer']); //register.tsx


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
