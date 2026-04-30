<?php

namespace App\DTO\API\Admin\CancelationReason;

use App\DTO\API\BaseAPIDTO;

final readonly class CreateCancelationReasonDTO extends BaseAPIDTO
{
    public function __construct(
        public string $reason,
    ) {
    }
}
