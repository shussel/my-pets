<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum SexEnum: string
{
    use EnumTraits;

    case MALE = 'male';
    case FEMALE = 'female';
}
