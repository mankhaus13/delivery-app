<?php

declare(strict_types=1);

namespace App\Actions\API\Mobile\WorkDay;

use App\DTO\API\Mobile\WorkDay\GetWorkDayDTO;
use App\Models\Bodycheck;
use App\Models\Brigade;
use App\Models\Order;
use App\Models\Shipping;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\API\Helpers\MqttPublish;
use App\Services\Enums\MqttTopic;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

final readonly class SendChangesAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private GetInfoAction $getInfo,
        private MqttPublish $mqtt,
    ) {
    }

    public function sendChangesToExpeditor(Model $model): void
    {
        Log::info('about to send workday changes via mqtt');

        try {
            $expeditorId = $this->getReceiverId($model);
            if (! $expeditorId) {
                throw new Exception('invalid order ids retrieved');
            }
            $this->sendData($expeditorId);
        } catch (Exception $e) {
            Log::error('work-day-mqtt', [
                'model' => $model->toArray(),
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    private function getReceiverId(Model $model): int
    {
        if (
            $model instanceof Bodycheck
            ||
            $model instanceof Shipping
            ||
            $model instanceof Order
        ) {
            return $model->expeditor_id;
        }

        if ($model instanceof Brigade) {
            return $this->userRepository->getUserIdByBrigadeId($model->id);
        }
        Log::error('work-day_mqtt', ['model' => $model->toArray(), 'message' => 'unable to retrieve recipient id']);
        throw new Exception('unable to retrieve recipient id');
    }

    private function sendData(int $expeditorId): void
    {
        //обновления интересуют только для сегодняшнего дня
        $workDayInfo = $this->getInfo->getInfo(new GetWorkDayDTO(date('Y-m-d'), $expeditorId));
        $this->mqtt->publish(
            MqttTopic::WORK_DAY,
            $expeditorId,
            json_encode($workDayInfo, JSON_UNESCAPED_UNICODE),
        );
    }
}
