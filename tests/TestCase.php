<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Support\Carbon;

abstract class TestCase extends BaseTestCase
{
    use WithFaker;

    protected function login(): User
    {
        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $response = $this->postJson('/api/login', $data);
        $response->assertStatus(200);

        $this->assertIsString($response['token']);
        $this->assertTrue(str_contains($response['token'], '|'));

        $this->assertIsString($response['expiresAt']);

        $date = Carbon::parse($response['expiresAt']);

        $this->assertTrue($date->gt(now()));

        $seconds = intval(config('sanctum.expiration'));
        $this->assertTrue($date->lte(now()->addSeconds($seconds)));

        return $user;
    }
}
