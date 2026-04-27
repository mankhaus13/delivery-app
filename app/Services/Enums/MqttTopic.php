<?php

namespace App\Services\Enums;

enum MqttTopic: string
{
    case WORK_DAY = 'workday/';
    case ACTIVE_ORDER = 'active_order/';
    case ORDERS = 'orders/';
    case CANCEL_REASONS = 'cancel-reasons/';
    case NOTIFICATIONS = 'notifications/';
    case DISCREPANCY_REASONS = 'discrepancy-reasons/';
}
