<?php

namespace Tests\Feature;

use App\Enum\TransactionStatus;
use App\Models\PaymentRequest;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\PaymentProvider;
use Tests\TestCase;

class EasyMoneyTest extends TestCase
{
    use RefreshDatabase;

    public function test_easy_no_login(): void
    {
        [$id, $uuid] = $this->getIds();

        $this->provider_no_login($id, $uuid);
    }

    public function test_easy_wrong_provider(): void
    {
        $uuid = $this->faker->uuid();
        $user = $this->login();

        $response = $this->actingAs($user)
            ->postJson("/api/payment/$uuid", $this->getPayload());

        $response->assertStatus(404)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', 'Record not found.'));
    }

    public function test_easy_zero_amount(): void
    {
        [$id, $uuid] = $this->getIds();
        $this->provider_no_login($id, $uuid);
    }

    public function test_easy_ok(): void
    {
        [$id, $uuid] = $this->getIds();
        $user = $this->login();
        $payload = $this->getPayload();

        $response = $this->actingAs($user)
            ->postJson("/api/payment/$uuid", $payload);

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->where('success', true)
                    ->has('transaction_id')
                );

        $transaction = Transaction::where('uuid', $response->json('transaction_id'))
            ->first();

        $this->assertNotNull($transaction);

        $this->assertSame($user->id, $transaction->user->id);
        $this->assertSame($id, $transaction->provider->id);
        $this->assertSame(TransactionStatus::Accepted, $transaction->status);

        $paymentRequest = PaymentRequest::where('transaction_id', $transaction->id)
            ->first();

        $this->assertNotNull($paymentRequest);

        $requestData = $paymentRequest->request;

        $this->assertArrayHasKey('url', $requestData);
        $this->assertSame('http://localhost:3000/process', $requestData['url']);

        $this->assertArrayHasKey('data', $requestData);
        $this->assertArrayHasKey('amount', $requestData['data']);
        $this->assertArrayHasKey('currency', $requestData['data']);

        $this->assertSame($payload['amount'], $requestData['data']['amount']);
        $this->assertSame($payload['currency'], $requestData['data']['currency']);

        $this->assertSame('ok',$paymentRequest->response);
    }

    private function getIds(): array
    {
        $provider = PaymentProvider::first();

        return [$provider->id, $provider->uuid];
    }
}
