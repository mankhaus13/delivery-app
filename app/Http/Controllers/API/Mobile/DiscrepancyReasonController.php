<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Services\API\Mobile\DiscrepancyReasonService\BottlesDiscrepancyReasonService;
use Illuminate\Http\JsonResponse;

final class DiscrepancyReasonController extends Controller
{
    public function __construct(private readonly BottlesDiscrepancyReasonService $service)
    {
    }

    /**
     * get all reasons why expeditor got less empty bottles than was stated in order
     */
    public function getAll(): JsonResponse
    {
        return $this->service->getAll()->response();
    }
}
