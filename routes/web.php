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
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('/customer', CustomerController::class);
    Route::resource('/cars', CarController::class);
    Route::resource('/subscriptions', SubscriptionController::class);
    Route::resource('/slots', SlotController::class);
    // في web.php
    Route::get('/cars/by-customer/{customer_id}', [CarController::class, 'getCarsByCustomer']);

    Route::get('/send-test-email', function () {
        Mail::raw('This is a test email via Gmail SMTP', function ($message) {
            $message->to(Auth::user()->email)->subject('Laravel SMTP Test');
        });
        return 'Email Sent!';
    });
});
