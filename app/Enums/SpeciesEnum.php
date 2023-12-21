<?php

namespace App\Enums;
;

use App\Enums\Traits\EnumValues;

enum SpeciesEnum: string
{
    use EnumValues;

    case DOG = 'dog';
    case CAT = 'cat';
    case FISH = 'fish';
    case BIRD = 'bird';
    case REPTILE = 'reptile';
}
