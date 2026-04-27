<?php

declare(strict_types=1);

namespace App\Models\Enums\Item;

use App\Models\Enums\EnumValuesTrait;

enum ItemType: string
{
    use EnumValuesTrait;

    case Default19 = 'default19';
    case Iodine19 = 'iodine19';
    case Coffee19 = 'coffee19';
    case PotassiumMagnesium19 = 'potassiumMagnesium19';
    case Default5 = 'default5';
    case PET19 = 'PET19';
    case Pump = 'Pump';
}
