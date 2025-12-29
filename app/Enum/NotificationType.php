<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum NotificationType: string
{
    use EnumOptions, EnumValues;

    case NOTIFICATION_SUCCESS = 'success';
    case NOTIFICATION_ERROR = 'error';
}
