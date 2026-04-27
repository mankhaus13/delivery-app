<?php

namespace App\Traits;

use App\Models\Enums\User\ActionsToLog;
use App\Models\UserLog;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Log;

use function storage_path;

trait Logger
{
    public function logRequest(string $log): void
    {
        $orderLog = new Log('ERP');
        $orderLog->pushHandler(new StreamHandler(storage_path('logs/ERP.log')));
        $orderLog->info('OrderLog', [$log]);
    }

    public function logAction(ActionsToLog $action, int $userId): void
    {
        UserLog::query()->create([
            'expeditor_id' => $userId,
            'action' => $action->value,
        ]);
    }
}
