<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderUpdated;
use App\Services\API\Mobile\Order\OrderListenerHelper\OrderListenerHelper;

final readonly class SendToERP
{
    public function __construct(private OrderListenerHelper $helper)
    {
    }

    /**
     * When the order being changed by expeditor, we send changed order to master system, so the administrators can see
     */
    public function handle(OrderUpdated $event): void
    {
        $this->helper->sendChangesToERP($event->order);
    }
}
