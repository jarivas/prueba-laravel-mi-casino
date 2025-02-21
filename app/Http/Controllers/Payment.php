<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment as PaymentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use App\Models\PaymentProvider;
use App\Models\Transaction;
use App\Enum\TransactionStatus;
use App\Models\PaymentRequest as ModelPaymentRequest;

class Payment extends Controller
{
    private const string ENDPOINT = 'webhook';
    private PaymentProvider $provider;
    private Transaction $transaction;


    public function __invoke(PaymentRequest $request, PaymentProvider $provider): JsonResponse
    {
        $this->provider = $provider;

        $this->transaction = Transaction::factory()->create(
            [
                'provider_id' => $provider->id,
                'user_id' => $request->user()->id,
                'amount' => $request->integer('amount'),
                'currency' => $request->string('currency')
            ]
        );

        // @phpstan-ignore match.unhandled
        return match ($provider->name) {
            'EasyMoney' => $this->requestEasyMoney(),
            'SuperWalletz' => $this->requestSuperWalletz()
        };
    }

    private function requestEasyMoney(): JsonResponse
    {
        $success = $this->send($this->preparePayload());

        $this->transaction->status = ($success) ? TransactionStatus::Accepted
            : TransactionStatus::Failed;

        $this->transaction->save();

        return response()->json(['success' => $success]);
    }

    private function requestSuperWalletz(): JsonResponse
    {
        $data = $this->preparePayload();

        $data['callback_url'] = route(self::ENDPOINT) . '/' . $this->transaction->uuid;

        $success = $this->send($data);

        return response()->json(['success' => $success]);
    }

    private function preparePayload(): array
    {
        return [
            'amount' => $this->transaction->amount,
            'currency' => $this->transaction->currency,
        ];
    }

    private function send(array $data): bool
    {
        $url = $this->provider->url;

        return $this->sendHelper($url, $data);
    }


    private function sendHelper(string $url, array $data): bool
    {
        $paymentRequest = $this->createPaymentRequest($url, $data);

        $response = Http::post($url, $data);

        $paymentRequest->response = $response->json();

        $paymentRequest->save();

        return $response->ok();
    }

    private function createPaymentRequest(string $url, array $data): ModelPaymentRequest
    {
        $request = [
            'url' => $url,
            'data' => $data,
        ];

        return ModelPaymentRequest::create([
            'request' => $request,
            'transaction_id' => $this->transaction->id,
        ]);
    }
}
