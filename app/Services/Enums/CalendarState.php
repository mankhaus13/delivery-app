<?php

namespace App\Services\Enums;

enum CalendarState: string
{
    case WORK_DAY = 'workDay';
    case VACATION = 'vacation';
    case WORK_DAY_AND_MORNING = 'workDayAndMorning';
    case WORK_DAY_AND_EVENING = 'workDayAndEvening';
}
