<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login;
use App\Http\Controllers\PaymentProvider;
use App\Http\Controllers\Payment;
use App\Http\Controllers\Webhook;

Route::post('/login', Login::class)->name('login');

Route::get('/payment_provider', [PaymentProvider::class, 'read'])
    ->middleware('auth:sanctum');

Route::post('/payment/{provider}', Payment::class)
    ->middleware('auth:sanctum')
    ->name('payment');

Route::post('/webhook/{transaction}', Webhook::class)->name('webhook');
