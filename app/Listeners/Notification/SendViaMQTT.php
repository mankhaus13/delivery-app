<?php

namespace App\Listeners\Notification;

use App\Events\Notification\NotificationCreated;
use App\Models\User;
use App\Services\API\Helpers\MqttPublish;
use App\Services\Enums\MqttTopic;
use Illuminate\Support\Facades\Log;

use function json_encode;

final readonly class SendViaMQTT
{
    public function __construct(
        private MqttPublish $mqtt,
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationCreated $event): void
    {
        Log::info('notification-mqtt');
        /** @var User $user */
        $user = User::query()->find($event->notification->expeditor_id);
        //пользователь может отказаться от рассылки пушей, тогда отправляю по мктт
        if (! $user->device_token) {
            $this->mqtt->publish(
                MqttTopic::NOTIFICATIONS,
                $user->id,
                json_encode($event->notification->toArray())
            );
        }
    }
}
