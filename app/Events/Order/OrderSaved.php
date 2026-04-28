<?php

namespace App\Events\Order;

use App\Models\Order;

final readonly class OrderSaved
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Order $order,
    ) {
    }
}
