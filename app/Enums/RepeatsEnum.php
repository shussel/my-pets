<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum RepeatsEnum: string
{
    use EnumTraits;

    case X_PER = 'times-per';
    case EVERY = 'every';
    case ONCE = 'once';
}
