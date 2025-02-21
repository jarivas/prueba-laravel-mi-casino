<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\PaymentProviderStatus;

/**
 * Summary of PaymentProvider
 *
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $url
 * @property \App\Enum\PaymentProviderStatus $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 */
class PaymentProvider extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PaymentProviderStatus::class,
        ];
    }
}
