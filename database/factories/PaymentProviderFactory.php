<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enum\PaymentProviderStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentProvider>
 */
class PaymentProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid(),
            'status' => PaymentProviderStatus::Active
        ];
    }
}
