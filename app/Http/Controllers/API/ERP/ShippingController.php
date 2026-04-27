<?php

namespace App\Http\Controllers\API\ERP;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ERP\Shipping\ShippingRequest;
use App\Services\API\ERP\Shipping\ShippingImporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class ShippingController extends Controller
{
    public function __construct(
        private readonly ShippingImporter $shippingImporter
    ) {
    }

    /**
     * receive instances from ERP and repopulates them into database
     */
    public function applyChanges(ShippingRequest $request): JsonResponse
    {
        Log::info('shipping erp', ['request' => $request->all()]);
        return $this->shippingImporter->applyChanges($request->getData())->response();
    }
}
