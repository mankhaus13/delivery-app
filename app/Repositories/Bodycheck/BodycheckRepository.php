<?php

declare(strict_types=1);

namespace App\Repositories\Bodycheck;

use App\Models\Bodycheck;

final readonly class BodycheckRepository
{
    public function getByExpeditor(string $date, int $expeditorId): ?Bodycheck
    {
        return Bodycheck::query()
            ->forDate($date)
            ->forExpeditor($expeditorId)
            ->select([
                'id',
                'starts_at',
            ])
            ->first();
    }
}
