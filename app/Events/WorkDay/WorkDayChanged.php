<?php

namespace App\Events\WorkDay;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Database\Eloquent\Model;

class WorkDayChanged implements ShouldDispatchAfterCommit
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Model $model, //событие будет зажигаться на обновление любой сущности из составляющий workday
    ) {
    }
}
