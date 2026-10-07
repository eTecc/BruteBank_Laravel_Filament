<?php

use BruteBank\LaravelFilament\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('brutebank/2fa')->name('brutebank.2fa.')->group(function () {
    Route::get('/', [TwoFactorController::class, 'show'])->name('challenge');
    Route::post('/', [TwoFactorController::class, 'verify'])->name('verify');
    Route::post('/code', [TwoFactorController::class, 'verifyCode'])->middleware('throttle:5,1')->name('code');
});
