<?php

declare(strict_types=1);

namespace App\Listeners\Order;

use App\Events\Order\OrderUpdating;
use App\Services\API\Mobile\Order\OrderListenerHelper\OrderListenerHelper;

final readonly class ActiveOrderChanged
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private OrderListenerHelper $helper,
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(OrderUpdating $event): void
    {
        $this->helper->sendActiveOrderChanges($event->order);
    }
}
