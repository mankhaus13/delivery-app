<?php

declare(strict_types=1);

namespace App\Services\API\Admin;

use App\Http\Resources\Admin\Notification\NotificationCollection;
use App\Models\Notification;

final readonly class NotificationService
{
    private const int PAGE_SIZE = 30; //записей на страницу

    public function getAll(): NotificationCollection
    {
        return new NotificationCollection(Notification::query()->paginate(self::PAGE_SIZE));
    }
}
