<?php

namespace App\Models\Enums;

use function array_column;

trait EnumValuesTrait
{
    /**
     * Retrieves values of enum
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
