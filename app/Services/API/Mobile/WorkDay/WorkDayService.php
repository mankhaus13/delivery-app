<?php

declare(strict_types=1);

namespace App\Services\API\Mobile\WorkDay;

use App\Actions\API\Mobile\WorkDay\GetInfoAction;
use App\DTO\API\Mobile\WorkDay\GetWorkDayDTO;
use App\Http\Resources\Mobile\WorkDay\WorkDayCollection;

final readonly class WorkDayService implements WorkDayServiceInterface
{
    public function __construct(
        private GetInfoAction $action,
    ) {
    }

    public function getInfo(GetWorkDayDTO $dto): WorkDayCollection
    {
        return $this->action->getInfo($dto);
    }
}
