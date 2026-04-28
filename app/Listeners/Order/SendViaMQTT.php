<?php

namespace App\Listeners\Order;

use App\DTO\API\Mobile\Order\GetOrdersDTO;
use App\Events\Order\OrderSaved;
use App\Services\API\Helpers\MqttPublish;
use App\Services\API\Mobile\Order\OrderManager\OrderServiceInterface;
use App\Services\Enums\MqttTopic;
use Illuminate\Support\Facades\Log;

use function json_encode;

final readonly class SendViaMQTT
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private OrderServiceInterface $orderService,//todo: наверно нужен более узкий интефейс
        private MqttPublish $mqtt,
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(OrderSaved $event): void
    {
        Log::info('sending order changes via mqtt');
        $order = $event->order;
        $dto = new GetOrdersDTO($order->shipping_date, $order->expeditor_id);

        $message = json_encode($this->orderService->getAll($dto), JSON_UNESCAPED_UNICODE);

        $this->mqtt->publish(
            mqttTopic: MqttTopic::ORDERS,
            expeditorId: $order->expeditor_id,
            message: $message,
        );
    }
}
