<?php

declare(strict_types=1);

namespace App\Events\BottlesDiscrepancyReason;

use App\Models\BottlesDiscrepancyReason;

final readonly class BottlesDiscrepancyReasonListChanged
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public BottlesDiscrepancyReason $reason,
    ) {
    }
}
