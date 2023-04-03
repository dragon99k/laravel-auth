<?php

namespace App\Enums\User;

use App\Enums\Concerns\Enumerate;
use App\Enums\Enum;

enum Status: string implements Enum
{
    use Enumerate;

    case Pending = 'Pending';
    case Activated = 'Activated';
    case Deactivated = 'Deactivated';
}
