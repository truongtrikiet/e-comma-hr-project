<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum TemplateStatus: int
{
    use EnumValues, EnumOptions;

    case MANUAL = 0;
    case AUTO = 1;
}
