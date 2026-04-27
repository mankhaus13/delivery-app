<?php

declare(strict_types=1);

namespace App\DTO\API\ERP\Item;

use App\DTO\API\BaseAPIDTO;

final readonly class ItemDTO extends BaseAPIDTO
{
    public function __construct(
        public string $image,
        public string $name,
        public int $quantity,
        public float $price,
        public ?string $type,
    ) {
    }
}
