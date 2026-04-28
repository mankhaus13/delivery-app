<?php

namespace App\Services\API\Mobile\Order\OrderStatusManager;

use App\DTO\API\Mobile\Order\CancelOrderDTO;
use App\DTO\API\Mobile\Order\CompleteOrderDTO;
use App\DTO\API\Mobile\Order\StartOrderDTO;
use Illuminate\Http\Resources\Json\JsonResource;

interface OrderStatusManagerInterface
{
    public function completeOrder(CompleteOrderDTO $dto): JsonResource;

    public function cancelOrder(CancelOrderDTO $dto): JsonResource;

    public function startOrder(StartOrderDTO $dto): JsonResource;
}
