<?php

namespace App\DTO\API\Admin\User;

use App\DTO\API\BaseAPIDTO;

final readonly class GetLogDTO extends BaseAPIDTO
{
    public function __construct(
        public int $expeditorId,
    ) {
    }
}
