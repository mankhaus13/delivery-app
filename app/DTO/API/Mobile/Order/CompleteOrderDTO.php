<?php

namespace App\DTO\API\Mobile\Order;

use App\DTO\API\BaseAPIDTO;

final readonly class CompleteOrderDTO extends BaseAPIDTO
{
    public function __construct(
        public int $orderId,
        public int $emptyBottles,
        public ?int $discrepancyReasonId = null,
    ) {
    }
}
