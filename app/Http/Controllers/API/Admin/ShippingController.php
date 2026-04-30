<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\API\Admin\ShippingService;
use Illuminate\Http\JsonResponse;

final class ShippingController extends Controller
{
    public function __construct(private readonly ShippingService $shippingService)
    {
    }

    /**
     * get all records paginated
     */
    public function getAll(): JsonResponse
    {
        return $this->shippingService->getAll()->response();
    }
}
