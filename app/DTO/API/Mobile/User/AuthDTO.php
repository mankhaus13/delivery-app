<?php

namespace App\DTO\API\Mobile\User;

use App\DTO\API\BaseAPIDTO;

final readonly class AuthDTO extends BaseAPIDTO
{
    public function __construct(
        public string $phone,
        public string $password,
    ) {
    }
}
