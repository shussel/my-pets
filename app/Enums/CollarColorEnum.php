<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;
use Illuminate\Support\Str;

enum CollarColorEnum: string
{
    use EnumTraits;

    case NONE = 'none';
    case RED = 'red';
    case PINK = 'pink';
    case ORANGE = 'orange';
    case YELLOW = 'yellow';
    case GREEN = 'green';
    case TEAL = 'teal';
    case BLUE = 'blue';
    case PURPLE = 'purple';
    case BLACK = 'black';
    case WHITE = 'white';
    case SILVER = 'silver';
    case GOLD = 'gold';

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
                'color' => $case->color(),
            ];
        }
        return $options;
    }

    public function color(): string
    {
        return match ($this) {
            CollarColorEnum::NONE => '',
            CollarColorEnum::RED => '#dc2626',
            CollarColorEnum::PINK => '#fecaca',
            CollarColorEnum::ORANGE => '#fb923c',
            CollarColorEnum::YELLOW => '#fef08a',
            CollarColorEnum::GREEN => '#84cc16',
            CollarColorEnum::TEAL => '#2dd4bf',
            CollarColorEnum::BLUE => '#2563eb',
            CollarColorEnum::PURPLE => '#9333ea',
            CollarColorEnum::BLACK => '#000000',
            CollarColorEnum::WHITE => '#ffffff',
            CollarColorEnum::SILVER => '#d1d5db',
            CollarColorEnum::GOLD => '#ca8a04'
        };
    }
}
