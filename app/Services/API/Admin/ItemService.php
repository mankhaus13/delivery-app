<?php

declare(strict_types=1);

namespace App\Services\API\Admin;

use App\Http\Resources\Admin\Item\ItemCollection;
use App\Models\Item;

final readonly class ItemService
{
    private const int PAGE_SIZE = 30; //записей на страницу

    public function getAll(): ItemCollection
    {
        return new ItemCollection(Item::query()->paginate(self::PAGE_SIZE));
    }
}
