<?php

namespace App\Models\Enums\Order;

use App\Models\Enums\EnumValuesTrait;

enum OrderStatus: string
{
    use EnumValuesTrait;

    case Active = 'active';
    case Completed = 'completed';
    case Pending = 'pending';
    case Canceled = 'canceled';
    case ToBeCanceled = 'to_be_canceled';
}
