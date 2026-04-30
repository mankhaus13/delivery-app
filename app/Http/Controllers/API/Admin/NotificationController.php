<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\API\Admin\NotificationService;
use Illuminate\Http\JsonResponse;

final class NotificationController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }

    /**
     * get all records paginated
     */
    public function getAll(): JsonResponse
    {
        return $this->notificationService->getAll()->response();
    }
}
