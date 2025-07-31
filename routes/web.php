<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\SubscriptionController;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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


Auth::routes([
    'verify' => true
]);
Route::get('/', function () {
    return view('welcom');
});
Route::middleware('auth', 'verified')->group(function () {


    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('/customer', CustomerController::class);
    Route::resource('/cars', CarController::class);
    Route::resource('/subscriptions', SubscriptionController::class);
    Route::resource('/slots', SlotController::class);
    // في web.php
    Route::get('/cars/by-customer/{customer_id}/{subscription_id?}', [CarController::class, 'getCarsByCustomer']);
    Route::post('/cars/search', [CarController::class, 'index'])->name('cars.search');
    Route::post('/slots/search', [SlotController::class, 'index'])->name('slots.search');
    Route::get('/subscriptios/search', [SubscriptionController::class, 'index'])->name('subscriptios.search');





    Route::post('/customers/{id}/update-status', [CustomerController::class, 'updateStatus']);
    Route::post('/slots/{id}/update-status', [SlotController::class, 'updateStatus']);
});
