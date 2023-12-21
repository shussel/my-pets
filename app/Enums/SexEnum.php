<?php

namespace App\Enums;

use App\Enums\Traits\EnumValues;

enum SexEnum: string
{
    use EnumValues;

    case MALE = 'male';
    case FEMALE = 'female';
}
