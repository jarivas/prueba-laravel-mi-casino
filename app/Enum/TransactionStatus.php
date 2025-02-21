<?php

namespace App\Enum;

enum TransactionStatus: string
{
    use Values;

    case Accepted = 'accepted';
    case Failed = 'failed';
    case InProgress = 'in_progress';
}
