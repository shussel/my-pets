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

    public function image(): string
    {
        return match ($this) {
            SpeciesEnum::DOG => 'https://wildcard.codestuff.io/dog/100/100',
            SpeciesEnum::CAT => 'https://wildcard.codestuff.io/cat/100/100',
            SpeciesEnum::FISH => 'https://wildcard.codestuff.io/fish/100/100',
            SpeciesEnum::BIRD => 'https://wildcard.codestuff.io/bird/100/100',
            SpeciesEnum::REPTILE => 'https://wildcard.codestuff.io/bug/100/100',
        };
    }
}
