<?php

namespace App\Services\API\Mobile\WorkDay;

use App\DTO\API\Mobile\WorkDay\GetWorkDayDTO;
use App\Http\Resources\Mobile\WorkDay\WorkDayCollection;

interface WorkDayServiceInterface
{
    public function getInfo(GetWorkDayDTO $dto): WorkDayCollection;
}
