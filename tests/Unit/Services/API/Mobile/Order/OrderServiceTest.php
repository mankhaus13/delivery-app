<?php

namespace Tests\Unit\Services\API\Mobile\Order;

use App\DTO\API\Mobile\Order\GetOrdersDTO;
use App\Http\Resources\Mobile\Order\ActiveOrderResource;
use App\Http\Resources\Mobile\Order\NotFoundOrderResource;
use App\Http\Resources\Mobile\Order\OrdersResource;
use App\Models\Enums\Order\OrderStatus;
use App\Models\Order;
use App\Services\API\Mobile\Order\OrderManager\OrderService;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

use function date;

#[CoversClass(OrderService::class)]
final class OrderServiceTest extends TestCase
{
    private readonly OrderService $orderService;

    public function __construct(string $name)
    {
        parent::__construct($name);
        //todo: найти норм способ инжектить зависимость
        $this->orderService = new OrderService();
    }

    public function testGetActive(): void
    {
        //assign
        $expeditorId = 1;
        Order::forExpeditor($expeditorId)->delete();

        /** @var Order $order */
        $order = Order::factory()->make();
        $order->expeditor_id = $expeditorId;
        $order->shipping_date = date('Y-m-d');
        $order->status = OrderStatus::Active->value;
        $order->saveQuietly();

        //act
        $result = $this->orderService->getActive($expeditorId);

        //assert
        $this->assertInstanceOf(ActiveOrderResource::class, $result);
    }

    public function testGetActiveFailed(): void
    {
        //assign
        $expeditorId = 1;
        Order::forExpeditor($expeditorId)->active()->delete();

        /** @var Order $order */
        $order = Order::factory()->make();
        $order->expeditor_id = $expeditorId;
        $order->shipping_date = date('Y-m-d');
        $order->status = OrderStatus::Canceled->value;
        $order->saveQuietly();

        //act
        $result = $this->orderService->getActive($expeditorId);

        //assert
        $this->assertInstanceOf(NotFoundOrderResource::class, $result);
    }

    public function testGetAllIsEmpty(): void
    {
        //Arrange
        $expeditorId = 1;
        Order::forExpeditor($expeditorId)->delete();

        $date = date('Y-m-d');
        $dto = new GetOrdersDTO($date, $expeditorId);

        //act
        $result = $this->orderService->getAll($dto);

        //assert
        $this->assertInstanceOf(NotFoundOrderResource::class, $result);
    }

    public function testGetAllNotEmpty(): void
    {
        //Arrange
        $date = date('Y-m-d');
        $expeditorId = 1;
        $dto = new GetOrdersDTO($date, $expeditorId);

        Order::forExpeditor($expeditorId)->delete();
        /** @var Order $order */
        $order = Order::factory()->make();
        $order->expeditor_id = $expeditorId;
        $order->shipping_date = date('Y-m-d');
        $order->saveQuietly();

        //act
        $result = $this->orderService->getAll($dto);

        //assert
        $this->assertInstanceOf(OrdersResource::class, $result);
    }
}
