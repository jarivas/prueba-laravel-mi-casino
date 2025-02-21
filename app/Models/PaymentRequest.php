<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Summary of PaymentRequest
 *
 * @property integer $id
 * @property array $request
 * @property array $response
 * @property integer $transaction_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 */
class PaymentRequest extends Model
{
}
