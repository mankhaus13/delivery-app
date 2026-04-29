<?php

namespace App\Events\CancelationReason;

use App\Models\CancelationReason;

final readonly class CancelationReasonListChanged
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public CancelationReason $cancelationReason,
    ) {
    }
}
