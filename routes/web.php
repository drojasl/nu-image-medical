<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/create', function () {
    return view('clients.index');
});

Auth::routes();

Route::get('/tfa', [App\Http\Controllers\Auth\TwoFactorAuthController::class, 'index'])->name('tfa');
Route::post('/tfa', [App\Http\Controllers\Auth\TwoFactorAuthController::class, 'validateCode'])->name('tfa.validate');
Route::post('/tfa/resend', [App\Http\Controllers\Auth\TwoFactorAuthController::class, 'resendCode'])->name('tfa.resend');

Route::group(['middleware' => '2FA'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/vin', [App\Http\Controllers\VinController::class, 'index'])->name('vin');
    Route::post('/vin', [App\Http\Controllers\VinController::class, 'search'])->name('vin.search');
});
