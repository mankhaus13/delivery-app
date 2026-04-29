<?php

namespace App\Services\API\Mobile\CancelationReason;

use Illuminate\Http\Resources\Json\ResourceCollection;

interface CancelationReasonServiceInterface
{
    public function getAll(): ResourceCollection;
}
