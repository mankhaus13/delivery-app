<?php

namespace App\DTO\API\ERP\Brigade;

use App\DTO\API\BaseAPIDTO;
use Illuminate\Support\Collection;

final readonly class BrigadeDTO extends BaseAPIDTO
{
    public function __construct(
        public string $expeditorId,
        public string $date,
        public string $period,
        public string $carId,
        public Collection $members,
    ) {
    }
}
