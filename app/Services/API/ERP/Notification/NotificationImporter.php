<?php

declare(strict_types=1);

namespace App\Services\API\ERP\Notification;

use App\DTO\API\ERP\Notification\NotificationDTO;
use App\Http\Resources\ERP\Notification\NotificationResource;
use App\Models\Enums\Notification\NotificationOrderStatus;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Services\API\ERP\ERPServiceInterface;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class NotificationImporter implements ERPServiceInterface
{
    public function __construct(private NotificationValidator $validator)
    {
    }

    public function applyChanges(Collection $records): NotificationResource
    {
        Log::info('Start notification validation');
        $storedRecords = 0;
        $totalRecords = 0;
        foreach ($records as $number => $notification) {
            $this->processNotification($notification, $number, $storedRecords, $totalRecords);
        }
        Log::info('All notifications imported successfully');

        return new NotificationResource(
            [
                'amountOfStoredRecords' => $storedRecords,
                'totalAmountOfRecords' => $totalRecords,
            ]
        );
    }

    private function processNotification(
        array $notification,
        int $number,
        int &$storedRecords,
        int &$totalAmountOfRecords
    ): void {
        try {
            $validNotification = $this->validator
                ->validateNotification($notification, $number, $totalAmountOfRecords);
        } catch (Exception) {
            return;
        }

        $notificationDTO = $this->validator->transformToNotificationDTO($validNotification);
        $this->saveNotification($notificationDTO);
        $storedRecords++;
        Log::info("notification {$notification['expeditor_id']} imported successfully");
    }

    private function saveNotification(NotificationDTO $dto): void
    {
        Notification::query()->create([
            'message' => $dto->message,
            'expeditor_id' => User::getIdByExternalId($dto->userExternalId),
            'order_id' => Order::query()->where('external_id', $dto->orderExternalId)->value('id'),
            'status' => $dto->status,
            'viewed' => false,
        ]);
    }
}
