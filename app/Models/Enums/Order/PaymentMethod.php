<?php

namespace App\Models\Enums\Order;

use App\Models\Enums\EnumValuesTrait;

enum PaymentMethod: string
{
    use EnumValuesTrait;

    case Paid = 'paid';
    case PaymentUponDelivery = 'payment_upon_delivery';
}
