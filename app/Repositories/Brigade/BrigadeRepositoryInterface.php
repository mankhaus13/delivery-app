<?php

namespace App\Repositories\Brigade;

use Illuminate\Support\Collection;

interface BrigadeRepositoryInterface
{
    public function getBrigadeIdByUserId(string $date, int $expeditorId): ?int;

    public function getMembersByBrigadeId(int $brigadeId): Collection;
}
