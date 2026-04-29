<?php

namespace App\DTO\API\Mobile\Calendar;

use App\DTO\API\BaseAPIDTO;

final readonly class GetCalendarDTO extends BaseAPIDTO
{
    public function __construct(
        public string $date,
        public int $expeditorId,
    ) {
    }
}
