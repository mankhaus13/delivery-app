<?php

namespace App\Services\API\Mobile\Calendar;

use App\DTO\API\Mobile\Calendar\GetCalendarDTO;
use Illuminate\Http\Resources\Json\JsonResource;

interface CalendarServiceInterface
{
    public function getInfo(GetCalendarDTO $dto): JsonResource;
}
