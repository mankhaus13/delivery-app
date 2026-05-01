<?php

declare(strict_types=1);

namespace App\Services\API\Helpers;

use App\Models\Notification;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class Push
{
    public function __construct(
        private Messaging $messaging,
    ) {
    }

    /**
     * @throws MessagingException
     * @throws FirebaseException
     * @throws Exception
     */
    public function sendPush(Notification $notification, User $user): void
    {
        if (!$user->isSubscribedForPushes()) {
            return;
        }
        try {
            $message = CloudMessage::withTarget('token', $user->getDeviceToken())
                ->withNotification(FirebaseNotification::create('Ключевая Вода', $notification->message))
                ->withData(['id' => (string) $notification->id]);

            $this->messaging->send($message);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
