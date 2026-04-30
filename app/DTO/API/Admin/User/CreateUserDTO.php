<?php

namespace App\DTO\API\Admin\User;

use App\DTO\API\BaseAPIDTO;

final readonly class CreateUserDTO extends BaseAPIDTO
{
    public function __construct(
        public string $password,
        public string $phone,
        public string $firstName,
        public string $secondName,
        public string $surname,
        public string $externalId,
    ) {
    }
}
