<?php

namespace Tests\Feature;


use App\Enum\TransactionStatus;
use App\Models\WebhookRequest;
use App\Models\PaymentRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\PaymentProvider;
use Tests\TestCase;

class SuperWalletzTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_no_login(): void
    {
        [$id, $uuid] = $this->getIds();

        $this->provider_no_login($id, $uuid);
    }

    public function test_super_zero_amount(): void
    {
        [$id, $uuid] = $this->getIds();
        $this->provider_no_login($id, $uuid);
    }

    public function test_super_ok_request(): Transaction
    {
        [$id, $uuid] = $this->getIds();
        $user = $this->login();
        $payload = $this->getPayload();

        $response = $this->actingAs($user)
            ->postJson("/api/payment/$uuid", $payload);

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->where('success', expected: true)
                    ->has('transaction_id')
                );

        return $this->validateAfterRequest($id, $user, $payload, $response->json('transaction_id'));
    }

    public function test_super_ok_webhook(): void
    {
        [$id, $uuid] = $this->getIds();
        $user = $this->login();
        $payload = $this->getPayload();

        $transaction = Transaction::factory()->create(
            array_merge(
            [
                'payment_provider_id' => $id,
                'user_id' => $user,
            ],
            $payload)
        );

        $webhookPayload = [
            'transaction_id' => 'trx_' . $this->faker->randomNumber(1,100000),
            'status' => 'success',
        ];

        $response = $this->postJson('/api/webhook/' . $transaction->uuid, $webhookPayload);

        $response->assertOk();

        $this->validateAfterWebhook($transaction);
    }

    public static function getIds(): array
    {
        $provider = PaymentProvider::all()[1];

        return [$provider->id, $provider->uuid];
    }

    private function validateAfterRequest(int $providerId, User $user, array $payload, string $uuid): Transaction
    {
        $transaction = Transaction::where('uuid', $uuid)
            ->first();

        $this->assertNotNull($transaction);

        $this->assertSame($user->id, $transaction->user->id);
        $this->assertSame($providerId, $transaction->provider->id);
        $this->assertSame(TransactionStatus::InProgress, $transaction->status);

        $paymentRequest = PaymentRequest::where('transaction_id', $transaction->id)
            ->first();

        $this->assertNotNull($paymentRequest);

        $requestData = $paymentRequest->request;

        $this->assertArrayHasKey('url', $requestData);
        $this->assertSame('http://localhost:3003/pay', $requestData['url']);

        $this->assertArrayHasKey('data', $requestData);
        $this->assertArrayHasKey('amount', $requestData['data']);
        $this->assertArrayHasKey('currency', $requestData['data']);
        $this->assertArrayHasKey('callback_url', $requestData['data']);

        $this->assertSame($payload['amount'], $requestData['data']['amount']);
        $this->assertSame($payload['currency'], $requestData['data']['currency']);

        $this->assertStringContainsString('trx_',$paymentRequest->response);

        return $transaction;
    }

    private function validateAfterWebhook(Transaction $transaction):void
    {
        $transaction->refresh();

        $this->assertSame(TransactionStatus::Accepted, $transaction->status);

        $webhook = WebhookRequest::where('transaction_id', $transaction->id)->first();

        $this->assertNotNull($webhook);

        $requestData = $webhook->request;

        $this->assertArrayHasKey('transaction_id', $requestData);
        $this->assertArrayHasKey('status', $requestData);

        $this->assertSame($transaction->external_id, $requestData['transaction_id']);
        $this->assertSame('success', $requestData['status']);

    }
}
