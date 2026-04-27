<?php

namespace App\DTO\API\ERP\Bodycheck;

use App\DTO\API\BaseAPIDTO;

final readonly class BodycheckDTO extends BaseAPIDTO
{
    public function __construct(
        public int $expeditorId,
        public string $timeStart,
        public string $date,
        public bool $passed,
    ) {
    }
}
