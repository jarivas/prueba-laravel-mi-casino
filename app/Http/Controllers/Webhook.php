<?php

namespace App\Http\Controllers;

use App\Enum\TransactionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Models\WebhookRequest;
use Illuminate\Support\Facades\Log;

class Webhook extends Controller
{
    private Transaction $transaction;
    private array $data;

    public function __invoke(Request $request, Transaction $transaction): Response
    {
        Log::debug($transaction->toArray());
        if ($transaction->status != TransactionStatus::InProgress) {
            return response()->noContent();
        }

        $this->transaction = $transaction;
        $this->data = $request->all();

        $this->saveWebhookRequest();

        // @phpstan-ignore match.unhandled
        return match ($transaction->provider->name) {
            'SuperWalletz' => $this->processSuperWalletz()
        };
    }

    private function saveWebhookRequest(): void
    {
        WebhookRequest::create([
            'request' => $this->data,
            'transaction_id' => $this->transaction->id,
        ]);
    }

    private function processSuperWalletz(): Response
    {
        $validator = Validator::make($this->data, [
            'transaction_id' => 'bail|required',
            'status' => 'required|in:success,failed',
        ]);

        $externalId = null;

        $isValid = !$validator->fails();

        if ($isValid && ($this->data['status'] == 'success')) {
            $externalId = $this->data['transaction_id'];
        }

        $this->saveTransaction($isValid, $externalId);

        return response()->noContent(200);
    }

    private function saveTransaction(bool $isValid, string|null $externalId = null): void
    {
        $this->transaction->status = ($isValid) ? TransactionStatus::Accepted
        : TransactionStatus::Failed;

        if ($externalId) {
            $this->transaction->external_id = $externalId;
        }

        $this->transaction->save();
    }
}
