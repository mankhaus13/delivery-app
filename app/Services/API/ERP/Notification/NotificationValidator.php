<?php

declare(strict_types=1);

namespace App\Services\API\ERP\Notification;

use App\DTO\API\ERP\Notification\NotificationDTO;
use App\Models\Enums\Notification\NotificationOrderStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Exception;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class NotificationValidator
{
    private function rules(): array
    {
        return [
            'message' => [
                'required',
                'string',
            ],
            'expeditor_id' => [
                'required',
                'uuid',
                'exists:users,external_id',
            ],
            'status' => [
                'required',
                'string',
                Rule::enum(NotificationOrderStatus::class),
            ],
            'order_id' => [
                'required',
                'string',
                'exists:orders,external_id',
            ],
        ];
    }

    /**
    * @throws ValidationException
    * @throws Exception
    */
    public function validateNotification(array $notification, int $number, int &$totalAmountOfRecords): array
    {
        Log::info("Validating notification {$notification['expeditor_id']}");
        $totalAmountOfRecords++;
        $validator = Validator::make($notification, $this->rules());

        if ($validator->stopOnFirstFailure()->fails()) {
            // Log validation errors for this notification
            Log::error(
                "Validation failed for notification at position $number with expeditor id
                {$notification['expeditor_id']}",
                ['errors' => $validator->errors()->toArray()]
            );
            throw new Exception('Validation failed'); // Skip this notification
        }
        return $validator->validated();
    }

    public function transformToNotificationDTO(array $notification): NotificationDTO
    {
        return new NotificationDTO(
            userExternalId: $notification['expeditor_id'],
            orderExternalId: $notification['order_id'],
            message: $notification['message'],
            status: $notification['status'],
        );
    }
}
