<?php

declare(strict_types=1);

namespace App\Services\API\Admin;

use App\Http\Resources\Admin\Shipping\ShippingCollection;
use App\Models\Shipping;

final readonly class ShippingService
{
    private const int PAGE_SIZE = 30; //записей на страницу

    public function getAll(): ShippingCollection
    {
        return new ShippingCollection(Shipping::with('expeditor')->paginate(self::PAGE_SIZE));
    }
}
