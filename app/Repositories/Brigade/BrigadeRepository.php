<?php

namespace App\Repositories\Brigade;

use App\Models\Brigade;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final readonly class BrigadeRepository implements BrigadeRepositoryInterface
{
    public function getBrigadeIdByUserId(string $date, int $expeditorId): ?int
    {
        return Brigade::query()
            ->where('date', $date)
            ->where('expeditor_id', $expeditorId)
            ->limit(1)
            ->value('id');
    }

    public function getMembersByBrigadeId(int $brigadeId): Collection
    {
        return DB::table('brigade_members')
            ->leftJoin('brigades', 'brigade_members.brigade_id', '=', 'brigades.id')
            ->where('brigades.id', $brigadeId)
            ->select([
                'fio as name',
                'telephone as phone',
                'position',
            ])
            ->get();
    }
}
