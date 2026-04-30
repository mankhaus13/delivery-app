<?php

declare(strict_types=1);

namespace App\Services\API\Admin;

use App\Http\Resources\Admin\Brigade\BrigadeCollection;
use App\Models\Brigade;

final readonly class BrigadeService
{
    private const int PAGE_SIZE = 30; //записей на страницу

    public function getAll(): BrigadeCollection
    {
        return new BrigadeCollection(Brigade::query()->paginate(self::PAGE_SIZE));
    }
}
