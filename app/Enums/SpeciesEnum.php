<?php

namespace App\Enums;

use App\Enums\Traits\EnumValues;

enum SpeciesEnum: string
{
    use EnumValues;

    case DOG = 'dog';
    case CAT = 'cat';
    case FISH = 'fish';
    case BIRD = 'bird';
    case REPTILE = 'reptile';

    public function maxAge(): int
    {
        return match ($this) {
            SpeciesEnum::DOG => 20,
            SpeciesEnum::CAT => 25,
            SpeciesEnum::FISH => 10,
            SpeciesEnum::BIRD => 60,
            SpeciesEnum::REPTILE => 30,
        };
    }

    public function maxWeight(): int
    {
        return match ($this) {
            SpeciesEnum::DOG => 80,
            SpeciesEnum::CAT, SpeciesEnum::REPTILE => 30,
            SpeciesEnum::FISH => 5,
            SpeciesEnum::BIRD => 9,
        };
    }
}
