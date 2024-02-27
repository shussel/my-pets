<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum PoopEnum: string
{
    use EnumTraits;

    case YARD = 'yard';
    case WALKS = 'walks';
    case KENNEL = 'kennel';
    case LITTERBOX = 'litterbox';
    case CAGE = 'cage';
    case TANK = 'tank';
    case POND = 'pond';
    case AVIARY = 'aviary';
    case STABLE = 'stable';
    case PASTURE = 'pasture';
}
