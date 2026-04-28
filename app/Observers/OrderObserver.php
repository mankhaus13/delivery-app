<?php

namespace App\Observers;

use App\Events\Order\OrderSaved;
use App\Events\Order\OrderSaving;
use App\Events\Order\OrderUpdated;
use App\Events\Order\OrderUpdating;
use App\Events\WorkDay\WorkDayChanged;
use App\Models\Order;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class OrderObserver implements ShouldHandleEventsAfterCommit
{
    public function saved(Order $order): void
    {
        event(new OrderSaved($order));
        event(new WorkDayChanged($order));
    }

    public function updated(Order $order): void
    {
        event(new OrderUpdated($order));
    }

        public function updating(Order $order): void
    {
        event(new OrderUpdating($order));
    }

}
