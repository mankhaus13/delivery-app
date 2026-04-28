<?php

namespace App\Services\API\Mobile\Order\OrderManager;

use App\DTO\API\Mobile\Order\GetOrdersDTO;
use Illuminate\Http\Resources\Json\JsonResource;

interface OrderServiceInterface
{
    /**
     * Get all orders for specified expeditor and date
     */
    public function getAll(GetOrdersDTO $dto): JsonResource;

    /**
     * Get active order for specified expeditor
     */
    public function getActive(int $userId): JsonResource;
}
