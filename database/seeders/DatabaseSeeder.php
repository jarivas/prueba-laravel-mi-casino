<?php

namespace Database\Seeders;

use App\Models\PaymentProvider;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        PaymentProvider::factory()->create([
            'name' => 'EasyMoney',
            'url' => 'http://localhost:3000/process'
        ]);

        PaymentProvider::factory()->create([
            'name' => 'SuperWalletz',
            'url' => 'http://localhost:3003/pay'
        ]);
    }
}
