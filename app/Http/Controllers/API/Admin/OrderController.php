<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Admin\Order\EditOrderRequest;
use App\Services\API\Admin\OrderService;
use Illuminate\Http\JsonResponse;

final class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    /**
     * get all records paginated
     */
    public function getAll(): JsonResponse
    {
        return $this->orderService->getAll()->response();
    }

    /**
     * edit order (only some of the fields can be edited)
    */
    public function edit(EditOrderRequest $request): JsonResponse
    {
        return $this->orderService->edit($request->toDTO())->response();
    }
}
