<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Mobile\Order\CancelOrderRequest;
use App\Http\Requests\API\Mobile\Order\CompleteOrderRequest;
use App\Http\Requests\API\Mobile\Order\GetOrdersRequest;
use App\Http\Requests\API\Mobile\Order\StartOrderRequest;
use App\Services\API\Mobile\Order\OrderManager\OrderServiceInterface;
use App\Services\API\Mobile\Order\OrderStatusManager\OrderStatusManagerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class OrderController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $orderService,
        private readonly OrderStatusManagerInterface $statusManager,
    ) {
    }

    /**
     * get all orders with items for the specified date and the current user
     */
    public function getAll(GetOrdersRequest $request): JsonResponse
    {
        return $this->orderService->getAll($request->toDTO())->response();
    }

    /**
     * get active order with its items
     * there's can be only one active order per expeditor. CAN return order with status to_be_canceled
     */
    public function getActive(): JsonResponse
    {
        return $this->orderService->getActive((int) Auth::id())->response();
    }

    /**
     * mark order as completed AND start the next one based on expected delivery time
     */
    public function complete(CompleteOrderRequest $request): JsonResponse
    {
        return $this->statusManager->completeOrder($request->toDTO())->response();
    }

    /**
     * set status to order to_be_canceled, send reason to ERP to approve cancellation
     */
    public function cancel(CancelOrderRequest $request): JsonResponse
    {
        return $this->statusManager->cancelOrder($request->toDTO())->response();
    }

    /**
     * Assign new active order at the start of the working day, when there's no active ones yet
     */
    public function start(StartOrderRequest $request): JsonResponse
    {
        return $this->statusManager->startOrder($request->toDTO())->response();
    }
}
