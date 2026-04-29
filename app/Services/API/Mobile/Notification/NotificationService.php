<?php

declare(strict_types=1);

namespace App\Services\API\Mobile\Notification;

use App\DTO\API\Mobile\Notification\ReadNotificationDTO;
use App\Http\Resources\Mobile\Notification\MarkAsReadNotificationResource;
use App\Http\Resources\Mobile\Notification\NotificationResource;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class NotificationService implements NotificationServiceInterface
{
    public function getAll(): NotificationResource
    {
        $userId = Auth::id();
        $readNotifications = Notification::recentViewed($userId)->get();
        $notReadNotifications = Notification::recentUnviewed($userId)->get();

        return new NotificationResource([
            'read' => $readNotifications,
            'unread' => $notReadNotifications,
        ]);
    }

    public function markAsRead(ReadNotificationDTO $dto): MarkAsReadNotificationResource
    {
        Notification::query()->where('id', $dto->id)->update(['viewed' => 1]);

        return new MarkAsReadNotificationResource([]);
    }
}
