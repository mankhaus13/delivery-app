<?php

namespace App\Models\Enums\Notification;

use App\Models\Enums\EnumValuesTrait;

enum NotificationOrderStatus: string
{
    use EnumValuesTrait;

    case Cancelled = 'cancelled';
    case TimeChanged = 'time_changed';
}
