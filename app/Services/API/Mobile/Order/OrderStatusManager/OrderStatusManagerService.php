<?php

declare(strict_types=1);

namespace App\Services\API\Mobile\Order\OrderStatusManager;

use App\DTO\API\Mobile\Order\CancelOrderDTO;
use App\DTO\API\Mobile\Order\CompleteOrderDTO;
use App\DTO\API\Mobile\Order\StartOrderDTO;
use App\Exceptions\Mobile\Order\IncorrectStatus;
use App\Http\Resources\Mobile\Order\CancelOrderCollection;
use App\Http\Resources\Mobile\Order\CompleteOrderResource;
use App\Http\Resources\Mobile\Order\FailedCancelOrderCollection;
use App\Http\Resources\Mobile\Order\StartOrderCollection;
use App\Models\CancelationReason;
use App\Models\Enums\Order\OrderStatus;
use App\Models\Enums\User\ActionsToLog;
use App\Models\Order;
use App\Services\API\Helpers\GuzzleERP;
use App\Services\Enums\ErpEndpoint;
use App\Traits\Logger as UserLogger;
use Exception;
use Illuminate\Support\Facades\DB;
use Override;
use Illuminate\Log\Logger;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class OrderStatusManagerService implements OrderStatusManagerInterface
{
    use UserLogger;

    public function __construct(
        private GuzzleERP $guzzleERP,
        private Logger $logger = new Logger(),
    ) {
    }

    /**
     * @throws IncorrectStatus
     */
    #[Override]
    public function startOrder(StartOrderDTO $dto): StartOrderCollection
    {
        //проверка целостности, чтоб не оказалось два заказа у юзера активными одновременно
        Order::forExpeditor($dto->expeditorId)
            ->active()
            ->update(['status' => OrderStatus::Pending->value]);

        /** @var Order $order */
        $order = Order::query()->find($dto->orderId);

        if (!$order->isPending()) {
            throw new IncorrectStatus(
                currentStatus: OrderStatus::from($order->status),
                desiredStatus: OrderStatus::Active
            );
        }

        $order->setStatus(OrderStatus::Active);

        $this->logAction(
            action: ActionsToLog::Start,
            userId: Order::getExpeditorIdByOrderId($dto->orderId),
        );

        return new StartOrderCollection(['message' => 'Изменен статус заказа']);
    }

    #[Override]
    public function cancelOrder(CancelOrderDTO $dto): CancelOrderCollection|FailedCancelOrderCollection
    {
        DB::beginTransaction();
        try {
            Order::query()
                ->where('id', $dto->orderId)
                //после рассмотрения менеджером, ерп пришлет актуальный статус
                ->update([
                    'status' => OrderStatus::ToBeCanceled->value,
                    'cancelation_reason_id' => $dto->reasonId,
                ]);

            $this->sendCancelDetailsToErp($dto);

            $this->logAction(
                action: ActionsToLog::Cancel,
                userId: Order::getExpeditorIdByOrderId($dto->orderId),
            );
            DB::commit();
            return new CancelOrderCollection([]);
        } catch (Exception) {
            DB::rollBack();

            return new FailedCancelOrderCollection([]);
        }
    }

    /**
     * @throws Exception
     */
    private function sendCancelDetailsToErp(CancelOrderDTO $dto): void
    {
        /** @var CancelationReason $reason */
        $reason = CancelationReason::query()->where('id', $dto->reasonId)->first();
        /** @var Order $order */
        $order = Order::query()->where('id', $dto->orderId)->first();
        $data = [
            'order_id' => $order->external_id,
            'order_date' => date('d.m.Y', strtotime($order->shipping_date)),
            'under_delivery_reason' => $reason->code ?? 2,
            'comment' => $reason->reason,
        ];

        $this->guzzleERP->pushChanges(ErpEndpoint::UnderDelivery, $data);
    }

    /**
     * Mark order as completed,
     * log that user completed given order,
     * activate the next one
     */
    public function completeOrder(CompleteOrderDTO $dto): CompleteOrderResource
    {
        DB::beginTransaction();
        try {
            Order::query()
                ->where('id', $dto->orderId)
                ->update([
                    'status' => OrderStatus::Completed->value,
                    'empty_bottles' => $dto->emptyBottles,
                    'discrepancy_reason_id' => $dto->discrepancyReasonId,
                ]);
            $userId = Order::getExpeditorIdByOrderId($dto->orderId);
            $this->logAction(
                action: ActionsToLog::Complete,
                userId: $userId,
            );
            $this->activateNextOrder($userId);
            DB::commit();
            return new CompleteOrderResource(['message' => 'Заказ успешно закрыт']);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            DB::rollBack();
            return new CompleteOrderResource(['message' => $e->getMessage()]);
        }
    }

    public function activateNextOrder(int $userId): void
    {
        /** @var Order $order */
        $order = Order::forExpeditor($userId)
            ->pending()
            ->forDate(date('Y-m-d'))
            ->orderBy('expected_delivery_time')
            ->first();

        if (!$order) {
            //просто не активируем, вполне могли кончится заказы
            return;
        }
        $order->setStatus(OrderStatus::Active);
    }
}
