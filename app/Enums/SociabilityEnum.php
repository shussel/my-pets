<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum SociabilityEnum: string
{
    use EnumTraits;

    case SHY = 'shy';
    case AVERAGE = 'average';
    case FRIENDLY = 'friendly';
    case MEAN = 'mean';
}
