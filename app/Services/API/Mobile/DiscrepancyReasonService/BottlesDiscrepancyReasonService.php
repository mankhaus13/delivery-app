<?php

declare(strict_types=1);

namespace App\Services\API\Mobile\DiscrepancyReasonService;

use App\Http\Resources\Mobile\BottlesDiscrepancyReason\BottlesDiscrepancyReasonCollection;
use App\Models\BottlesDiscrepancyReason;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class BottlesDiscrepancyReasonService
{
    public function getAll(): BottlesDiscrepancyReasonCollection
    {
        return new BottlesDiscrepancyReasonCollection(BottlesDiscrepancyReason::all());
    }
}
