<?php

namespace App\Models\Enums\User;

use App\Models\Enums\EnumValuesTrait;

enum ActionsToLog: string
{
    use EnumValuesTrait;

    case Auth = 'auth';
    case Start = 'order_start';
    case Complete = 'order_complete';
    case Cancel = 'order_cancel';
}
