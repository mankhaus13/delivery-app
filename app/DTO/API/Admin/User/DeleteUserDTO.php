<?php

namespace App\DTO\API\Admin\User;

use App\DTO\API\BaseAPIDTO;

final readonly class DeleteUserDTO extends BaseAPIDTO
{
    public function __construct(
        public int $id,
    ) {
    }
}
