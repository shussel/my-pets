<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;
use Illuminate\Support\Str;

enum SpeciesEnum: string
{
    use EnumTraits;

    case DOG = 'dog';
    case CAT = 'cat';
    case BIRD = 'bird';
    case FISH = 'fish';
    case HORSE = 'horse';
    case REPTILE = 'reptile';

    public function maxAge(): int
    {
        return match ($this) {
            SpeciesEnum::DOG => 20,
            SpeciesEnum::CAT => 25,
            SpeciesEnum::FISH => 10,
            SpeciesEnum::BIRD => 60,
            SpeciesEnum::REPTILE => 30,
            SpeciesEnum::HORSE => 30,
        };
    }

    public function maxWeight(): int
    {
        return match ($this) {
            SpeciesEnum::DOG => 80,
            SpeciesEnum::CAT, SpeciesEnum::REPTILE => 30,
            SpeciesEnum::FISH => 5,
            SpeciesEnum::BIRD => 9,
            SpeciesEnum::HORSE => 2200,
        };
    }

    public function image(): string
    {
        return match ($this) {
            SpeciesEnum::DOG => 'https://wildcard.codestuff.io/dog/100/100',
            SpeciesEnum::CAT => 'https://wildcard.codestuff.io/cat/100/100',
            SpeciesEnum::BIRD => 'https://wildcard.codestuff.io/bird/100/100',
            SpeciesEnum::FISH => 'https://wildcard.codestuff.io/fish/100/100',
            SpeciesEnum::HORSE => 'https://wildcard.codestuff.io/horse/100/100',
            SpeciesEnum::REPTILE => 'https://wildcard.codestuff.io/bug/100/100',
        };
    }

    public function poop(): array
    {
        return match ($this) {
            SpeciesEnum::DOG => $this->toOptions([PoopEnum::YARD, PoopEnum::WALKS, PoopEnum::KENNEL]),
            SpeciesEnum::CAT => $this->toOptions([PoopEnum::LITTERBOX, PoopEnum::YARD, PoopEnum::WALKS]),
            SpeciesEnum::FISH => $this->toOptions([PoopEnum::TANK, PoopEnum::POND]),
            SpeciesEnum::BIRD => $this->toOptions([PoopEnum::CAGE, PoopEnum::AVIARY]),
            SpeciesEnum::REPTILE => $this->toOptions([PoopEnum::TANK]),
            SpeciesEnum::HORSE => $this->toOptions([PoopEnum::STABLE, PoopEnum::PASTURE]),
        };
    }

    public function sleep(): array
    {
        return match ($this) {
            SpeciesEnum::DOG => $this->toOptions([
                sleepEnum::INSIDE, sleepEnum::BED, sleepEnum::CRATE, sleepEnum::KENNEL, sleepEnum::DOG_HOUSE,
                sleepEnum::OUTSIDE
            ]),
            SpeciesEnum::CAT => $this->toOptions([
                sleepEnum::INSIDE, sleepEnum::BED, sleepEnum::CRATE, sleepEnum::OUTSIDE
            ]),
            SpeciesEnum::FISH => $this->toOptions([sleepEnum::TANK, sleepEnum::POND]),
            SpeciesEnum::BIRD => $this->toOptions([sleepEnum::CAGE, sleepEnum::AVIARY]),
            SpeciesEnum::REPTILE => $this->toOptions([sleepEnum::TANK]),
            SpeciesEnum::HORSE => $this->toOptions([sleepEnum::STABLE, sleepEnum::PASTURE]),
        };
    }

    public static function options(): array
    {
        $cases = static::cases();
        $options = [];
        foreach ($cases as $case) {
            $label = $case->name;
            if (Str::contains($label, '_')) {
                $label = Str::replace('_', ' ', $label);
            }
            $options[] = [
                'value' => $case->value,
                'label' => Str::title($label),
                'maxAge' => $case->maxAge(),
                'maxWeight' => $case->maxWeight(),
                'poop' => $case->poop(),
                'sleep' => $case->sleep(),
            ];
        }
        return $options;
    }

    public function toOptions($optionList)
    {
        foreach ($optionList as $option) {
            $options[] = [
                'value' => $option->value,
                'label' => Str::title(Str::replace('_', ' ', $option->name)),
            ];
        }
        return $options;
    }
}
