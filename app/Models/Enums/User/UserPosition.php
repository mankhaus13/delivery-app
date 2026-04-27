<?php

namespace App\Models\Enums\User;

use App\Models\Enums\EnumValuesTrait;

enum UserPosition: string
{
    use EnumValuesTrait;

    case Loader = 'грузчик';
    case Driver = 'водитель';
    case Expeditor = 'экспедитор';
    case DriverExpeditor = 'водитель-экспедитор';
    case LoaderIntern = 'грузчик-стажер';
    case ExpeditorIntern = 'экспедитор-стажер';
}
