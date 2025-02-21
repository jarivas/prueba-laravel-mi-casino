<?php

namespace App\Enum;

enum TransactionType: string
{
    use Values;

    case Pay = 'pay';
    case Refund = 'refund';
}
