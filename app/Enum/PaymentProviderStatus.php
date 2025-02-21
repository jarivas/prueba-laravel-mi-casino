<?php

namespace App\Enum;

enum PaymentProviderStatus: string
{
    use Values;
    
    case Active = 'active';
    case Inactive = 'inactive';
}
