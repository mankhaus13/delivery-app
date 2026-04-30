<?php

declare(strict_types=1);

namespace App\DTO\API\Admin\BottlesDiscrepancyReason;

use App\DTO\API\BaseAPIDTO;

final readonly class CreateDTO extends BaseAPIDTO
{
    public function __construct(
        public string $reason,
    ) {
    }
}
