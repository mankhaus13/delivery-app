<?php

namespace App\Http\Controllers\API\ERP;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ERP\Order\OrderRequest;
use App\Http\Requests\API\ERP\Order\ResolveToBeCanceledStatusRequest;
use App\Services\API\ERP\Order\OrderImporter;
use App\Services\API\ERP\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class OrderController extends Controller
{
    public function __construct(
        private readonly OrderImporter $orderImporter,
        private readonly OrderService $orderService
    ) {
    }

    /**
     * receive instances from ERP and repopulates them into database
     */
    public function applyChanges(OrderRequest $request): JsonResponse
    {
        return $this->orderImporter->applyChanges($request->getData())->response();
    }

    /**
     * Receive dispatcher's decision about whether confirm order cancellation or to wait client
     * @param ResolveToBeCanceledStatusRequest $request
     * @return JsonResponse
     */
    public function resolveToBeCanceledStatus(ResolveToBeCanceledStatusRequest $request): JsonResponse
    {
        Log::info('order erp', ['request' => $request->all()]);
        return $this->orderService->resolveToBeCanceledStatus($request->toDTO())->response();
    }
}
