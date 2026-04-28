<?php

namespace App\DTO\API\Mobile\Order;

use App\DTO\API\BaseAPIDTO;

/**
 * @param  int  $orderId
 * @param int $reasonId
 */
final readonly class CancelOrderDTO extends BaseAPIDTO
{
    public function __construct(
        public int $orderId,
        public int $reasonId,
    ) {
    }
}
