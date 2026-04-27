<?php

namespace App\DTO\API\ERP\Shipping;

use App\DTO\API\BaseAPIDTO;

final readonly class ShippingDTO extends BaseAPIDTO
{
    public function __construct(
        public string $date,
        public int $expeditorId,
        public string $window_number,
        public string $time_start,
        public string $time_end,
    ) {
    }
}
