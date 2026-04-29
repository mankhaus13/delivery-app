<?php

declare(strict_types=1);

namespace App\Services\API\Mobile\CancelationReason;

use App\Http\Resources\Mobile\CancelationReason\CancelationReasonCollection;
use App\Models\CancelationReason;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class CancelationReasonService implements CancelationReasonServiceInterface
{
    public function getAll(): CancelationReasonCollection
    {
        //todo оч редко будет меняться, стоит кэшировать
        return new CancelationReasonCollection(CancelationReason::all());
    }
}
