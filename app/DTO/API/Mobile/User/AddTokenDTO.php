<?php

namespace App\DTO\API\Mobile\User;

use App\DTO\API\BaseAPIDTO;

final readonly class AddTokenDTO extends BaseAPIDTO
{
    public function __construct(
        public string $token,
        public int $expeditorId,
    ) {
    }
}
