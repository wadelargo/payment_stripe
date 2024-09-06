<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeController;


Route::get('/', [StripeController::class, 'index']);
Route::post('/stripe', [StripeController::class, 'stripe'])->name('stripe');
Route::get('/success', [StripeController::class, 'success'])->name('success');
Route::get('/cancel', [StripeController::class, 'cancel'])->name('cancel');
