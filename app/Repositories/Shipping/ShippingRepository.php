<?php

declare(strict_types=1);

namespace App\Repositories\Shipping;

use App\Models\Shipping;
use Illuminate\Support\Collection;

final readonly class ShippingRepository
{
    public function getByDateAndExpeditor(string $date, int $expeditorId): Collection
    {
        return Shipping::query()
            ->forDate($date)
            ->forExpeditor($expeditorId)
            ->select([
                'id',
                'window_number',
                'date',
                'time_start',
                'time_end',
            ])
            ->get();
    }
}
