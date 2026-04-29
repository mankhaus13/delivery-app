<?php

namespace App\Listeners\Notification;

use App\Events\Notification\NotificationCreated;
use App\Models\User;
use App\Services\API\Helpers\MqttPublish;
use App\Services\API\Helpers\Push;
use App\Services\Enums\MqttTopic;
use Exception;
use Illuminate\Support\Facades\Log;

use function json_encode;

final readonly class SendPush
{
    public function __construct(
        private MqttPublish $mqtt,
        private Push $push,
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationCreated $event): void
    {
        Log::info('notification-push');
        /** @var User $user */
        $user = User::query()->find($event->notification->expeditor_id);
        try {
            $this->push->sendPush($event->notification, $user);
        } catch (Exception) {
            //если пуш не улетел, отправлю уведомление в mqtt
            $this->mqtt->publish(MqttTopic::NOTIFICATIONS, $user->id, json_encode($event->notification->toArray()));
        }
    }
}
