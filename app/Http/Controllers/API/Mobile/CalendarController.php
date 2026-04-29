<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Mobile\Calendar\GetCalendarRequest;
use App\Services\API\Mobile\Calendar\CalendarServiceInterface;
use Illuminate\Http\JsonResponse;

final class CalendarController extends Controller
{
    public function __construct(private readonly CalendarServiceInterface $calendarService)
    {
    }

    /**
     * Calendar is printing days in certain colors based on their statuses
     */
    public function __invoke(GetCalendarRequest $request): JsonResponse
    {
        return $this->calendarService->getInfo($request->toDTO())->response();
    }
}
