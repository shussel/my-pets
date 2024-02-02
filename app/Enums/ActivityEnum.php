<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum ActivityEnum: string
{
    use EnumTraits;

    case CALM = 'calm';
    case AVERAGE = 'average';
    case ACTIVE = 'active';
    case SPECIAL = 'special';
}
