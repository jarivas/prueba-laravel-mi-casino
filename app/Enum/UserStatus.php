<?php

namespace App\Enum;

enum UserStatus: string
{
    use Values;
    
    case Active = 'active';
    case Inactive = 'inactive';
    case Blocked = 'blocked';
}
