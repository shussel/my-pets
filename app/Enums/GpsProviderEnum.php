<?php

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum GpsProviderEnum: string
{
    use EnumTraits;

    case DOGTRA = 'dogtra';
    case FI = 'fi';
    case GARMIN = 'garmin';
    case GEOBIT = 'geobit';
    case HALO = 'halo';
    case SPORTDOG = 'sportdog';
    case TRACTIVE = 'tractive';
    case WHISTLE = 'whistle';
    case OTHER = 'other';
}
