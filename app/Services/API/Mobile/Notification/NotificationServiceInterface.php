<?php

namespace App\Services\API\Mobile\Notification;

use App\DTO\API\Mobile\Notification\ReadNotificationDTO;
use Illuminate\Http\Resources\Json\JsonResource;

interface NotificationServiceInterface
{
    public function getAll(): JsonResource;

    public function markAsRead(ReadNotificationDTO $dto): JsonResource;
}
