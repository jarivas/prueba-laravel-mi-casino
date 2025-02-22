<?php

namespace Tests\Feature;

use App\Enum\TransactionStatus;
use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RunningDevServerTest extends TestCase
{
    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = false;

    public function test_server_login_ok(): void
    {
        $this->getLoginInfo();
    }

    public function test_server_pay_easy_ok(): void
    {
        [$id, $uuid] = EasyMoneyTest::getIds();

        [$json, $payload, $user] = $this->server_pay_helper($id, $uuid);

        $this->assertDatabaseHas('transactions', [
            'uuid' => $json['transaction_id'],
            'amount' => $payload['amount'],
            'currency' => $payload['currency'],
            'user_id' => $user->id,
            'payment_provider_id' => $id,
            'status' => TransactionStatus::Accepted->value
        ]);
    }

    public function test_server_pay_super_ok(): void
    {
        [$id, $uuid] = SuperWalletzTest::getIds();

        [$json, $payload, $user] = $this->server_pay_helper($id, $uuid);

        sleep(10);

        $this->assertDatabaseHas('transactions', [
            'uuid' => $json['transaction_id'],
            'amount' => $payload['amount'],
            'currency' => $payload['currency'],
            'user_id' => $user->id,
            'payment_provider_id' => $id,
            'status' => TransactionStatus::Accepted->value
        ]);
    }

    private function server_pay_helper(int $id, string $uuid): array
    {
        [$user, $token] = $this->getLoginInfo();

        $url = route('payment', $uuid);
        $payload = $this->getPayload();

        $response = $this->send($url, $payload, $token);

        $json = $response->json();

        $this->assertArrayHasKey('success', $json);
        $this->assertArrayHasKey('transaction_id', $json);

        $this->assertTrue($json['success']);

        return [$json, $payload, $user];
    }

    private function getLoginInfo(): array
    {
        $user = User::factory()->create();
        $url = route('login');

        $response = Http::asJson()
            ->post($url, [
                'email' => $user->email,
                'password' => 'password'
        ]);

        $this->assertTrue($response->ok());

        $json = $response->json();

        $this->assertArrayHasKey('token', $json);
        $this->assertArrayHasKey('expiresAt', $json);

        return [$user, $json['token']];
    }

    private function send(string $url, array $data, string $token) :Response
    {
        return Http::asJson()
            ->withToken($token)
            ->post($url, $data);
    }
}
