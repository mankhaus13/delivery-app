<?php

namespace App\DTO\API\Mobile\WorkDay;

use App\DTO\API\BaseAPIDTO;

final readonly class GetWorkDayDTO extends BaseAPIDTO
{
    public function __construct(
        public string $date,
        public int $expeditorId,
    ) {
    }
}
