<?php

namespace App\Services\Enums;

enum ErpEndpoint: string
{
    case UnderDelivery = 'under-delivery';
    case ChangeStatus = 'change_status';
}
