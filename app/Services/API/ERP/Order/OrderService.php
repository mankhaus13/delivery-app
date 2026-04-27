<?php

declare(strict_types=1);

namespace App\Services\API\ERP\Order;

use App\DTO\API\ERP\Order\ResolveToBeCanceledStatusDTO;
use App\Http\Resources\ERP\Order\ResolveToBeCanceledStatusResource;
use App\Models\Enums\Order\OrderStatus;
use App\Models\Order;
use App\Services\API\Mobile\Order\OrderStatusManager\OrderStatusManagerService;
use Illuminate\Support\Facades\DB;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class OrderService
{
    public function __construct(
        private OrderStatusManagerService $mobileOrderService,
    ) {
    }

    public function resolveToBeCanceledStatus(ResolveToBeCanceledStatusDTO $dto): ResolveToBeCanceledStatusResource
    {
        /** @var Order $order */
        $order = Order::whereExternalId($dto->orderExternalId)->first();
        //нужно здесь для правильной отработки события обновления активного заказа
        DB::beginTransaction();
        if ($dto->status === OrderStatus::Canceled->value) {
            $order->update(['status' => OrderStatus::Canceled->value]);
            $this->mobileOrderService->activateNextOrder($order->expeditor_id);
        } elseif ($dto->status === OrderStatus::Active->value) {
            $order->update([
                'status' => OrderStatus::Active->value,
                'wait_until' => $dto->waitUntil,
            ]);
            //иначе упорно не сохраняет
            $order->wait_until = $dto->waitUntil;
            $order->save();
        }

        DB::commit();

        return new ResolveToBeCanceledStatusResource([]);
    }
}
