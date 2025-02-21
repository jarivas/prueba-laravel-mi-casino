<?php

namespace App\Models;

use App\Enum\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enum\TransactionStatus;

/**
 * Summary of Transaction
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $amount
 * @property string $currency
 * @property \App\Enum\TransactionType $type
 * @property \App\Enum\TransactionStatus $status
 * @property \App\Models\Transaction | null $parent
 * @property string | null $external_id
 * @property \App\Models\User $user
 * @property \App\Models\PaymentProvider $provider
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 */
class Transaction extends Model
{
    use HasFactory;

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function provider(): HasOne
    {
        return $this->hasOne(PaymentProvider::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
            'status' => TransactionStatus::class,
        ];
    }
}
