<?php

declare(strict_types=1);

namespace App\Services\API\Mobile\Order\OrderManager;

use App\DTO\API\Mobile\Order\GetOrdersDTO;
use App\Http\Resources\Mobile\Order\ActiveOrderResource;
use App\Http\Resources\Mobile\Order\NotFoundOrderResource;
use App\Http\Resources\Mobile\Order\OrdersResource;
use App\Models\Order;
use Illuminate\Support\Collection;

final readonly class OrderService implements OrderServiceInterface
{
    public function getAll(GetOrdersDTO $dto): NotFoundOrderResource|OrdersResource
    {
        /** @var Collection $orders */
        $orders = Order::forExpeditor($dto->expeditorId)
            ->where('shipping_date', $dto->date)
            ->orderBy('expected_delivery_time')
            ->with('items')
            ->get();

        if ($orders->isEmpty()) {
            return new NotFoundOrderResource([]);
        }

        return new OrdersResource(['data' => $orders]);
    }

    /**
     * can return to_be_canceled order, cuz after request to cancel being made, we wait to admin to confirm
     * or decline cancellation
     */
    public function getActive(int $userId): ActiveOrderResource|NotFoundOrderResource
    {
        $order = Order::forExpeditor($userId)
            ->activeOrToBeCanceled()
            ->orderBy('expected_delivery_time')
            ->with('items')
            ->first();
        if (! $order) {
            return new NotFoundOrderResource(['message' => 'Активный заказ не найден']);
        }

        return new ActiveOrderResource($order);
    }
}
