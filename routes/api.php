<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login;
use App\Http\Controllers\Payment;
use App\Http\Controllers\Webhook;

Route::post('/login', Login::class);

Route::post('/payment/{payment_providers:uuid}', Payment::class)
    ->middleware('auth:sanctum');

    Route::post('/webhook/{transactions:uuid}', Webhook::class)
    ->middleware('auth:sanctum');
