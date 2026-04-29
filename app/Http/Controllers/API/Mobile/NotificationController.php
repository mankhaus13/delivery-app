<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Mobile\Notification\ReadNotificationRequest;
use App\Services\API\Mobile\Notification\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;

final class NotificationController extends Controller
{
    public function __construct(private readonly NotificationServiceInterface $notificationService)
    {
    }

    /**
     * get all notifications for current date and current user
     * notification informs about expected delivery time being changed, or order being canceled
     */
    public function getAll(): JsonResponse
    {
        return $this->notificationService->getAll()->response();
    }

    /**
     * set status of notification to viewed
     * after this it won't be bold-typed
     */
    public function markAsRead(ReadNotificationRequest $request): JsonResponse
    {
        return $this->notificationService->markAsRead($request->toDTO())->response();
    }
}
