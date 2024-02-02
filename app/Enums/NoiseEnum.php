<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum NoiseEnum: string
{
    use EnumTraits;

    case SILENT = 'silent';
    case QUIET = 'quiet';
    case AVERAGE = 'average';
    case NOISY = 'noisy';
}
