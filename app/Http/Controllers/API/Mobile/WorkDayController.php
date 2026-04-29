<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Mobile\WorkDay\GetWorkDayRequest;
use App\Services\API\Mobile\WorkDay\WorkDayServiceInterface;
use Illuminate\Http\JsonResponse;

final class WorkDayController extends Controller
{
    public function __construct(private readonly WorkDayServiceInterface $workDayService)
    {
    }

    /**
     * Workday info includes brigade, shipping, bodycheck and other info to display on the main screen
     */
    public function __invoke(GetWorkDayRequest $request): JsonResponse
    {
        return $this->workDayService->getInfo($request->toDTO())->response();
    }
}
