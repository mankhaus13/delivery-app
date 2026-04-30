<?php

declare(strict_types=1);

namespace App\Services\API\Admin;

use App\DTO\API\Admin\Order\EditOrderDTO;
use App\Http\Resources\Admin\Order\OrderCollection;
use App\Http\Resources\Admin\Order\OrderResource;
use App\Models\Order;

final readonly class OrderService
{
    private const int PAGE_SIZE = 30; //записей на страницу

    public function getAll(): OrderCollection
    {
        return new OrderCollection(Order::query()
            ->with(
                [
                'expeditor',
                'discrepancyReason',
                'cancelationReason'
                ]
            )
            ->paginate(self::PAGE_SIZE));
    }

    public function edit(EditOrderDTO $dto): OrderResource
    {
        $order = Order::query()->where('id', $dto->id)->first();
        $order->update([
            'expeditor_id' => $dto->expeditorId,
            'address' => $dto->address,
            'return_bottles' => $dto->returnBottles,
            'empty_bottles' => $dto->emptyBottles,
        ]);
        return new OrderResource($order);
    }
}
