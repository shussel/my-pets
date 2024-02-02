<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum ActivityEnum: string
{
    use EnumTraits;

    case LOW = 'low';
    case AVERAGE = 'average';
    case HIGH = 'high';
    case SPECIAL = 'special';
}
