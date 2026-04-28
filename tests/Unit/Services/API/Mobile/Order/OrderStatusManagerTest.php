<?php

namespace Tests\Unit\Services\API\Mobile\Order;

use App\DTO\API\Mobile\Order\CancelOrderDTO;
use App\DTO\API\Mobile\Order\CompleteOrderDTO;
use App\DTO\API\Mobile\Order\StartOrderDTO;
use App\Exceptions\Mobile\Order\IncorrectStatus;
use App\Http\Resources\Mobile\Order\CancelOrderCollection;
use App\Http\Resources\Mobile\Order\CompleteOrderResource;
use App\Http\Resources\Mobile\Order\StartOrderCollection;
use App\Models\CancelationReason;
use App\Models\Enums\Order\OrderStatus;
use App\Models\Order;
use App\Services\API\Helpers\GuzzleERP;
use App\Services\API\Mobile\Order\OrderStatusManager\OrderStatusManagerService;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

use function date;

#[CoversClass(OrderStatusManagerService::class)]
final class OrderStatusManagerTest extends TestCase
{
    private readonly OrderStatusManagerService $orderStatusService;

    public function __construct(string $name)
    {
        parent::__construct($name);
        //todo: найти норм способ инжектить зависимость
        $guzzle = new GuzzleERP();
        $this->orderStatusService = new OrderStatusManagerService($guzzle);
    }

    public function testCancelOrder(): void
    {
        //assign
        $expeditorId = 1;
        Order::forExpeditor($expeditorId)->delete();

        /** @var Order $order */
        $order = Order::factory()->make();
        $order->expeditor_id = $expeditorId;
        $order->shipping_date = date('Y-m-d');
        $order->status = OrderStatus::Pending->value;
        $order->saveQuietly();

        $dto = new CancelOrderDTO($order->id, CancelationReason::query()->get()->random()->id);

        // Create a mock instance of GuzzleERP
        $guzzleMock = $this->createMock(GuzzleERP::class);

        $guzzleMock
            ->method('pushChanges')
            ->willReturn('dummy_string');

        $guzzleStub = $this->createStub(GuzzleERP::class);
        $guzzleStub->method('pushChanges')->willReturn('dummy_string');

        $orderService = new OrderStatusManagerService($guzzleMock);

        //act
        $result = $orderService->cancelOrder($dto);

        //assert
        $order->refresh();
        //todo: доделать, когда ерп будет готово принимать реквесты
        $this->assertEquals($order->status, OrderStatus::ToBeCanceled->value);
        $this->assertInstanceOf(CancelOrderCollection::class, $result);
    }

    public function testCompleteOrder(): void
    {
        //assign
        $expeditorId = 1;
        Order::forExpeditor($expeditorId)->delete();

        /** @var Order $order */
        $order = Order::factory()->make();
        $order->expeditor_id = $expeditorId;
        $order->shipping_date = date('Y-m-d');
        $order->status = OrderStatus::Pending->value;
        $order->saveQuietly();

        $emptyBottles = 5;
        $dto = new CompleteOrderDTO($order->id, $emptyBottles);

        //act
        $result = Order::withoutEvents(function () use ($dto) {
            return $this->orderStatusService->completeOrder($dto);
        });
        $order->refresh();

        //assert
        $this->assertInstanceOf(CompleteOrderResource::class, $result);
        $this->assertEquals(OrderStatus::Completed->value, $order->status);
        $this->assertEquals($emptyBottles, $order->empty_bottles);
    }

    public function testStartOrder(): void
    {
        //assign
        $expeditorId = 1;
        Order::forExpeditor($expeditorId)->delete();

        /** @var Order $order */
        $order = Order::factory()->make();
        $order->expeditor_id = $expeditorId;
        $order->shipping_date = date('Y-m-d');
        $order->status = OrderStatus::Pending->value;
        $order->saveQuietly();

        $dto = new StartOrderDTO(orderId: $order->id, expeditorId: $expeditorId);

        //act
        $result = Order::withoutEvents(function () use ($dto) {
            return $this->orderStatusService->startOrder($dto);
        });

        //assert
        $order->refresh();
        $this->assertInstanceOf(StartOrderCollection::class, $result);
        $this->assertEquals(OrderStatus::Active->value, $order->status);
    }

    public function testStartOrderFailed(): void
    {
        //assign
        $expeditorId = 1;
        Order::forExpeditor($expeditorId)->delete();

        /** @var Order $order */
        $order = Order::factory()->make();
        $order->expeditor_id = $expeditorId;
        $order->shipping_date = date('Y-m-d');
        //cannot start canceled order
        $order->status = OrderStatus::Canceled->value;
        $order->saveQuietly();

        $dto = new StartOrderDTO(orderId: $order->id, expeditorId: $expeditorId);

        //act & assert
        $this->expectException(IncorrectStatus::class);

        Order::withoutEvents(function () use ($dto) {
            $this->orderStatusService->startOrder($dto);
        });
    }
}
