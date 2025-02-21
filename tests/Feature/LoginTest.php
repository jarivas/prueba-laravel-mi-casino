<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_wrong_email(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->faker->email(),
            'password'=> $this->faker->password(),
        ]);

        $response->assertStatus(404)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('error', 'user_not_found'));
    }

    public function test_login_wrong_password(): void
    {
        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
            'password' => 'asdasd'
        ];

        $response = $this->postJson('/api/login', $data);
        $response->assertStatus(400)
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('error', 'invalid_credentials'));

    }

    public function test_login_ok(): void
    {
        $this->login();
    }
}
