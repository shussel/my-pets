<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum IntelligenceEnum: string
{
    use EnumTraits;

    case DUMB = 'dumb';
    case AVERAGE = 'averge';
    case SMART = 'smart';
    case SPECIAL = 'special';
}
