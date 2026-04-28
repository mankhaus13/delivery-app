<?php

declare(strict_types=1);

namespace App\Services\API\Mobile\Order\OrderListenerHelper;

use App\Http\Resources\Mobile\Order\ActiveOrderResource;
use App\Models\Order;
use App\Services\API\Helpers\GuzzleERP;
use App\Services\API\Helpers\MqttPublish;
use App\Services\Enums\ErpEndpoint;
use App\Services\Enums\MqttTopic;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class OrderListenerHelper
{
    public function __construct(
        private GuzzleERP $guzzleERP,
        private MqttPublish $mqtt,
    ) {
    }

    /**
     * Push changed order to ERP to keep systems synchronized
     * Made for listener
     */
    public function sendChangesToERP(Order $order): void
    {
        //todo: probably should be removed completely or completely refactored
        Log::info('sending order changes to erp');
        try {
            $this->guzzleERP->pushChanges(ErpEndpoint::ChangeStatus, $order->toArray());
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
        }
    }

    /**
     * Когда изменилось состояние активного заказа (например, отменили)
     */
    public function sendActiveOrderChanges(Order $order): void
    {
        if (!$order->isActive() && !$order->isToBeCanceled()) {
            //прекращаем выполнение события
            return;
        }
        Log::info('send active order mqtt');

        $message = json_encode(new ActiveOrderResource($order->load('items')), JSON_UNESCAPED_UNICODE);

        $this->mqtt->publish(MqttTopic::ACTIVE_ORDER, $order->expeditor_id, $message);
    }
}
