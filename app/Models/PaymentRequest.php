<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Summary of PaymentRequest
 *
 * @property integer $id
 * @property array $request
 * @property string $response
 * @property integer $transaction_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 */
class PaymentRequest extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'request',
        'response',
        'transaction_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'request' => 'array',
        ];
    }
}
