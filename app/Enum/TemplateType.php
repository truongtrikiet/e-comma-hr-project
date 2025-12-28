<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum TemplateType: int
{
    use EnumValues, EnumOptions;

    case NORMAL = 1;
    case BIRTHDAY = 2;
}
