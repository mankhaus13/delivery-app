<?php

namespace App\Repositories\User;

use App\Models\Brigade;

final readonly class UserRepository implements UserRepositoryInterface
{
    public function getUserIdByBrigadeId(int $brigadeId): int
    {
        return Brigade::query()->where('id', $brigadeId)->value('expeditor_id');
    }
}
