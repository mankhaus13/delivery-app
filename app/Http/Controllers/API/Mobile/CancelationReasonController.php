<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Services\API\Mobile\CancelationReason\CancelationReasonService;
use Illuminate\Http\JsonResponse;

final class CancelationReasonController extends Controller
{
    public function __construct(private readonly CancelationReasonService $service)
    {
    }

    /**
     * get all reasons to select why order being canceled
     */
    public function getAll(): JsonResponse
    {
        return $this->service->getAll()->response();
    }
}
