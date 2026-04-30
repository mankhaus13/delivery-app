<?php

namespace App\DTO\API\Admin\Order;

use App\DTO\API\BaseAPIDTO;

final readonly class EditOrderDTO extends BaseAPIDTO
{
    public function __construct(
        public int $id,
        public int $expeditorId,
        public string $address,
        public int $returnBottles,
        public int $emptyBottles,
    ) {
    }
}
