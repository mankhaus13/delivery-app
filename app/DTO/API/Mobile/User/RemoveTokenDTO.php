<?php

namespace App\DTO\API\Mobile\User;

use App\DTO\API\BaseAPIDTO;

final readonly class RemoveTokenDTO extends BaseAPIDTO
{
    public function __construct(
        public string $token,
    ) {
    }
}
