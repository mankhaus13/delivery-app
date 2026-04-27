<?php

namespace App\DTO\API\ERP\Notification;

use App\DTO\API\BaseAPIDTO;

final readonly class NotificationDTO extends BaseAPIDTO
{
    public function __construct(
        public string $userExternalId,
        public string $orderExternalId,
        public string $message,
        public string $status,
    ) {
    }
}
