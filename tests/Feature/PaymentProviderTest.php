<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class PaymentProviderTest extends TestCase
{
    public function test_payment_provider_ok(): void
    {
        $user = $this->login();

        $response = $this->actingAs($user)->get('/api/payment_provider');

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(2)
                    ->first(fn (AssertableJson $json) =>
                        $json->has('uuid')
                        ->where('name', 'EasyMoney')
                        ->where('url', 'http://localhost:3000/process')
                        ->where('status', 'active')
                        ->etc()
                    )
            );
    }
}
