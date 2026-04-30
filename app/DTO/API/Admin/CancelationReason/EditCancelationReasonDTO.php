<?php

namespace App\DTO\API\Admin\CancelationReason;

use App\DTO\API\BaseAPIDTO;

final readonly class EditCancelationReasonDTO extends BaseAPIDTO
{
    public function __construct(
        public int $id,
        public string $reason,
    ) {
    }
}
