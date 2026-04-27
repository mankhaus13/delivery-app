<?php

declare(strict_types=1);

namespace App\DTO\API\ERP\Order;

use App\DTO\API\BaseAPIDTO;

final readonly class WaitClientDTO extends BaseAPIDTO
{
    public function __construct(
        public string $orderExternalId,
        public string $timeToWait,
    ) {
    }
}
