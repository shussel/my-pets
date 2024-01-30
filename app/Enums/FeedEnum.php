<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum FeedEnum: string
{
    use EnumTraits;

    case ONCE = 'once';
    case TWICE = 'twice';
    case MULTIPLE = 'multi';
    case FREE_FEED = 'free';
}
