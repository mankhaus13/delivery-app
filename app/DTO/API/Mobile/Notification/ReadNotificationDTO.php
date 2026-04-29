<?php

namespace App\DTO\API\Mobile\Notification;

use App\DTO\API\BaseAPIDTO;

final readonly class ReadNotificationDTO extends BaseAPIDTO
{
    public function __construct(
        public int $id,
    ) {
    }
}
