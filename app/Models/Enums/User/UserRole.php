<?php

namespace App\Models\Enums\User;

use App\Models\Enums\EnumValuesTrait;

enum UserRole: string
{
    use EnumValuesTrait;

    /** может попасть в админку, но не может попасть в приложеньку */
    case Admin = 'admin';
    /** может попасть в приложеньку, но не может попасть в админку */
    case Expeditor = 'expeditor';
    /** может попасть везде, сделано для удобства разработки и тестирования */
    case Developer = 'developer';
}
