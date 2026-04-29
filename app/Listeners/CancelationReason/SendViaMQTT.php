<?php

namespace App\Listeners\CancelationReason;

use App\Events\CancelationReason\CancelationReasonListChanged;
use App\Http\Resources\Mobile\CancelationReason\CancelationReasonResource;
use App\Models\User;
use App\Services\API\Helpers\MqttPublish;
use App\Services\Enums\MqttTopic;
use Illuminate\Support\Facades\Log;

use function json_encode;

final readonly class SendViaMQTT
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private MqttPublish $mqtt,
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(CancelationReasonListChanged $event): void
    {
        Log::info('cancel-reason-mqtt');

        $userIds = User::query()->select('id')->pluck('id')->toArray();
        $message = json_encode(new CancelationReasonResource($event->cancelationReason), JSON_UNESCAPED_UNICODE);

        //рассылаю всем юзерам
        foreach ($userIds as $userId) {
            $this->mqtt->publish(MqttTopic::CANCEL_REASONS, $userId, $message);
        }
    }
}
