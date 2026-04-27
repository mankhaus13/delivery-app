<?php

declare(strict_types=1);

namespace App\DTO\API\ERP\Member;

use App\DTO\API\BaseAPIDTO;

final readonly class MemberDTO extends BaseAPIDTO
{
    public function __construct(
        public string $fio,
        public string $telephone,
        public string $position,
    )
    {
    }
}
