<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum SleepEnum: string
{
    use EnumTraits;

    case INSIDE = 'inside';
    case CRATE = 'crate';
    case KENNEL = 'kennel';
    case DOG_HOUSE = 'dog-house';
    case OUTSIDE = 'outside';
    case CAGE = 'cage';
    case TANK = 'tank';
    case POND = 'pond';
    case AVIARY = 'aviary';
    case STABLE = 'stable';
    case PASTURE = 'pasture';
}
