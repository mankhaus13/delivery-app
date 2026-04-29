<?php

namespace App\Events\Notification;

use App\Models\Notification;

class NotificationCreated
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Notification $notification,
    ) {
    }
}
