<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum TemplateObjectType: int
{
    use EnumValues, EnumOptions;

    case USER = 1;
    case CUSTOMER = 2;
}
