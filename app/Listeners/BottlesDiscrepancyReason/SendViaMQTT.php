<?php

namespace App\Listeners\BottlesDiscrepancyReason;

use App\Events\BottlesDiscrepancyReason\BottlesDiscrepancyReasonListChanged;
use App\Http\Resources\Mobile\BottlesDiscrepancyReason\BottlesDiscrepancyReasonResource;
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
    public function handle(BottlesDiscrepancyReasonListChanged $event): void
    {
        Log::info('discrepancy-reason-mqtt');

        $userIds = User::query()->select('id')->pluck('id')->toArray();
        $message = json_encode(new BottlesDiscrepancyReasonResource($event->reason), JSON_UNESCAPED_UNICODE);

        //рассылаю всем юзерам
        foreach ($userIds as $userId) {
            $this->mqtt->publish(MqttTopic::DISCREPANCY_REASONS, $userId, $message);
        }
    }
}
