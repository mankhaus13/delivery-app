<?php

namespace App\DTO\API\Admin\CancelationReason;

use App\DTO\API\BaseAPIDTO;

final readonly class DeleteCancelationReasonDTO extends BaseAPIDTO
{
    public function __construct(
        public int $id,
    ) {
    }
}
