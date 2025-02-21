<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Summary of WebhookRequest
 *
 * @property integer $id
 * @property array $request
 * @property integer $transaction_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 */
class WebhookRequest extends Model
{
}
