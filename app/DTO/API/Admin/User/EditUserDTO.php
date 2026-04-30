<?php

namespace App\DTO\API\Admin\User;

use App\DTO\API\BaseAPIDTO;

final readonly class EditUserDTO extends BaseAPIDTO
{
    public function __construct(
        public int $id,
        public string $phone,
        public string $password,
        public string $first_name,
        public string $second_name,
        public string $surname,
    ) {
    }
}
