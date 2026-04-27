<?php

namespace App\Models\Enums\Order;

use App\Models\Enums\EnumValuesTrait;

enum Period: string
{
    use EnumValuesTrait;

    case Morning = 'morning';
    case Day = 'day';
    case Evening = 'evening';
}
