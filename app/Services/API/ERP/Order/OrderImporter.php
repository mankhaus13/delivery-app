<?php

declare(strict_types=1);

namespace App\Services\API\ERP\Order;

use App\DTO\API\ERP\Item\ItemDTO;
use App\DTO\API\ERP\Order\OrderDTO;
use App\Http\Resources\ERP\Order\OrderResource;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use App\Services\API\ERP\ERPServiceInterface;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 * the validation logic moved here due to necessity to validate orders independently
 * TODO: must be moved in queue dispatcher job
 */
final readonly class OrderImporter implements ERPServiceInterface
{
    public function __construct(
        private OrderValidator $orderValidator
    ) {
    }

    public function applyChanges(Collection $records): OrderResource
    {
        Log::info('Start order validation');
        $amountOfStoredOrders = 0;
        $totalAmountOfOrders = 0;
        foreach ($records as $number => $order) {
            $this->processOrder($order, $number, $amountOfStoredOrders, $totalAmountOfOrders);
        }
        Log::info('All orders imported successfully');

        return new OrderResource(
            [
                'amountOfStoredOrders' => $amountOfStoredOrders,
                'totalAmountOfOrders' => $totalAmountOfOrders
            ]
        );
    }

    private function processOrder(
        array $order,
        int $number,
        int &$amountOfStoredOrders,
        int &$totalAmountOfOrders,
    ): void {
        try {
            $orderDTO = $this->orderValidator->transformToOrderDTO(
                $this->orderValidator->validateOrder($order, $number, $totalAmountOfOrders)
            );
        } catch (Exception) {
            return;
        }
        $expeditorId = User::query()
            ->where('external_id', $orderDTO->expeditor_id)
            ->value('id');

        $orderExists = Order::query()->where('external_id', $orderDTO->external_id)->exists();
        $orderExists ? $this->updateOrder($orderDTO, $expeditorId) : $this->saveOrder($orderDTO, $expeditorId);

        $amountOfStoredOrders++;
        Log::info("Order {$order['external_id']} imported successfully");
    }

    private function updateOrder(OrderDTO $dto, int $expeditorId): void
    {
        /**в одной транзакции для нормальной работы событий и целостности */
        DB::beginTransaction();
        /** @var Order $order */
        $order = Order::query()->where('external_id', $dto->external_id)->first();
        $order->update([
            'expeditor_id' => $expeditorId,
            'return_bottles' => $dto->return_bottles,
            'payment_method' => $dto->payment_method,
            'status' => $dto->status,
            'period' => $dto->period,
            'client_name' => $dto->client_name,
            'address' => $dto->address,
            'address_extra_info' => $dto->address_extra_info,
            'shipping_date' => $dto->shipping_date,
            'total' => $dto->total,
            'expected_delivery_time' => $dto->expected_delivery_time,
            'order_comment' => $dto->order_comment,
            'address_comment' => $dto->address_comment,
            'empty_bottles' => 0, //todo: probably set default value
            'number' => $dto->number,
        ]);

        Item::query()->where('order_id', $order->id)->delete();
        foreach ($dto->items as $item) {
            $this->saveItem($item, $order->id);
        }
        DB::commit();
    }

    private function saveOrder(OrderDTO $dto, int $expeditorId): void
    {
        /**в одной транзакции для нормальной работы событий и целостности */
        DB::beginTransaction();
        /** @var Order $order */
        $order = Order::query()->create([
            'external_id' => $dto->external_id,
            'expeditor_id' => $expeditorId,
            'return_bottles' => $dto->return_bottles,
            'payment_method' => $dto->payment_method,
            'status' => $dto->status,
            'period' => $dto->period,
            'client_name' => $dto->client_name,
            'address' => $dto->address,
            'address_extra_info' => $dto->address_extra_info,
            'shipping_date' => $dto->shipping_date,
            'total' => $dto->total,
            'expected_delivery_time' => $dto->expected_delivery_time,
            'order_comment' => $dto->order_comment,
            'address_comment' => $dto->address_comment,
            'empty_bottles' => 0, //todo: probably set default value
            'number' => $dto->number,
        ]);
        foreach ($dto->items as $item) {
            $this->saveItem($item, $order->id);
        }
        DB::commit();
    }

    private function saveItem(ItemDTO $item, int $orderId): void
    {
        Item::query()->create([
            'order_id' => $orderId,
            'image' => $item->image,
            'name' => $item->name,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'type' => $item->type,
        ]);
    }
}
