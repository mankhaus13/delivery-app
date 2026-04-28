<?php

namespace App\Exceptions\Mobile\Order;

use App\Exceptions\APIException;
use App\Models\Enums\Order\OrderStatus;
use Symfony\Component\HttpFoundation\Response;

final class IncorrectStatus extends APIException
{
    public function __construct(OrderStatus $currentStatus, OrderStatus $desiredStatus)
    {
        $this->customMessage = "Order status $currentStatus->value cannot be changed to $desiredStatus->value";
        $this->httpCode = Response::HTTP_BAD_REQUEST;
    }
}
