<?php

namespace App\Listeners\WorkDay;

use App\Actions\API\Mobile\WorkDay\SendChangesAction;
use App\Events\WorkDay\WorkDayChanged;

final readonly class SendViaMQTT
{
    public function __construct(private SendChangesAction $action)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(WorkDayChanged $event): void
    {
        $this->action->sendChangesToExpeditor($event->model);
    }
}
