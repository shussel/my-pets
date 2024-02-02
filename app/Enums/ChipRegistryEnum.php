<?php
/* https://peeva.co/resources/microchip-guide */

namespace App\Enums;

use App\Enums\Traits\EnumTraits;
use Illuminate\Support\Str;

enum ChipRegistryEnum: string
{
    use EnumTraits;

    case PETWATCH = '24-petwatch';
    case AKC_Reunite = 'AKC-reunite';
    case AVID = 'avid';
    case PETLINK = 'petlink';
    case BUDDY_ID = 'buddy-id';
    case HOME_AGAIN = 'home-again';
    case PEEVA = 'peeva';
    case PETKEY = 'petkey';
    case OTHER = 'other';

    public static function options(): array
    {
        $cases = static::cases();
        $options = [];
        foreach ($cases as $case) {
            $label = $case->name;
            if ($label === 'PETWATCH') {
                $label = "24 PetWatch";
            } else {
                if (Str::contains($label, '_')) {
                    $label = Str::replace('_', ' ', $label);
                }
            }
            $options[] = [
                'value' => $case->value,
                'label' => Str::title($label),
                'phone' => $case->phone(),
            ];
        }
        return $options;
    }

    public function phone(): string
    {
        return match ($this) {
            ChipRegistryEnum::PETWATCH => '866.597.2424',
            ChipRegistryEnum::AKC_Reunite => '800.252.7894',
            ChipRegistryEnum::AVID => '800.336.2843',
            ChipRegistryEnum::PETLINK => '877.738.5465',
            ChipRegistryEnum::BUDDY_ID => '800.434.2843',
            ChipRegistryEnum::HOME_AGAIN => '888.466.3242',
            ChipRegistryEnum::PEEVA => '833.733.8226',
            ChipRegistryEnum::PETKEY => '734.600.3463',
            ChipRegistryEnum::OTHER => '',
        };
    }
}
