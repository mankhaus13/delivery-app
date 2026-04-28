<?php

namespace App\DTO\API\Mobile\Order;

use App\DTO\API\BaseAPIDTO;

final readonly class StartOrderDTO extends BaseAPIDTO
{
    public function __construct(
        public int $orderId,
        public int $expeditorId,
    ) {
    }
}
