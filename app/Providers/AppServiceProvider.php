<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\PaymentProvider;
use App\Models\Transaction;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('provider', function (string $value) {
            return PaymentProvider::where('uuid', $value)->first();
        });
        Route::bind('transaction', function (string $value) {
            return Transaction::where('uuid', $value)->first();
        });
    }
}
