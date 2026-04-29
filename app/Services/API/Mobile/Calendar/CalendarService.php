<?php

declare(strict_types=1);

namespace App\Services\API\Mobile\Calendar;

use App\DTO\API\Mobile\Calendar\GetCalendarDTO;
use App\Http\Resources\Mobile\Calendar\CalendarResource;
use App\Models\Brigade;
use App\Services\Enums\CalendarState;
use Carbon\Carbon;

use function min;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class CalendarService implements CalendarServiceInterface
{
    public function getInfo(GetCalendarDTO $dto): CalendarResource
    {
        $currentDate = Carbon::parse($dto->date);
        $currentDay = $this->getCurrentDayOfMonth($currentDate);

        $calendarInfo = $this->generateCalendarInfo($currentDate, $currentDay, $dto->expeditorId);

        return new CalendarResource($calendarInfo);
    }

    private function getCurrentDayOfMonth(Carbon $date): int
    {
        $currentMonth = Carbon::now()->month;

        if ($date->month === $currentMonth) {
            return Carbon::now()->day;
        }

        return $date->daysInMonth;
    }

    private function generateCalendarInfo(Carbon $date, int $currentDay, int $expeditorId): array
    {
        $calendarInfo = [];

        for ($i = 1; $i <= min($date->daysInMonth, $currentDay); $i++) {
            $currentDate = $date->copy()->day($i);
            $state = $this->determineStateForDay($currentDate, $expeditorId);
            $calendarInfo[$currentDate->format('d')] = $state->value;
        }

        return $calendarInfo;
    }

    private function determineStateForDay(Carbon $date, int $expeditorId): CalendarState
    {
        $brigades = Brigade::forExpeditor($expeditorId)
            ->forDate($date->format('Y-m-d'))
            ->get();

        if ($brigades->isEmpty()) {
            return CalendarState::VACATION;
        }

        $state = CalendarState::WORK_DAY;

        foreach ($brigades as $brigade) {
            if ($brigade->hasEveningShift()) {
                $state = CalendarState::WORK_DAY_AND_EVENING;
            }

            if ($brigade->hasMorningShift()) {
                $state = CalendarState::WORK_DAY_AND_MORNING;
            }
        }

        return $state;
    }
}
