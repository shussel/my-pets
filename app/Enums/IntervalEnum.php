<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum IntervalEnum: int
{
    use EnumTraits;

    case YEAR = 3628800;
    case MONTH = 302400;
    case WEEK = 10080;
    case DAY = 1440;
    case HOUR = 60;
    case MINUTE = 1;
}
