<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login;
use App\Http\Controllers\Payment;
use App\Http\Controllers\Webhook;

Route::post('/login', Login::class);

Route::post('/payment/{provider}', Payment::class)
    ->middleware('auth:sanctum');

    Route::post('/webhook/{transaction}', Webhook::class)
    ->middleware('auth:sanctum');
