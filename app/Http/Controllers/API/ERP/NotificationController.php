<?php

namespace App\Http\Controllers\API\ERP;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ERP\Notification\NotificationRequest;
use App\Services\API\ERP\Notification\NotificationImporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationImporter $notificationService,
    ) {
    }

    /**
     * receive instances from ERP and repopulates them into database
     */
    public function applyChanges(NotificationRequest $request): JsonResponse
    {
        Log::info('notifications erp', ['request' => $request->all()]);
        return $this->notificationService->applyChanges($request->getData())->response();
    }
}
