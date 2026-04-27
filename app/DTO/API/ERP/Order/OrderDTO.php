<?php

namespace App\DTO\API\ERP\Order;

use App\DTO\API\BaseAPIDTO;
use App\DTO\API\ERP\Item\ItemDTO;
use Illuminate\Support\Collection;

final readonly class OrderDTO extends BaseAPIDTO
{
    public function __construct(
        public string $external_id,
        public string $expeditor_id,
        public string $number,
        public string $shipping_date,
        public int $return_bottles,
        public string $payment_method,
        public string $status,
        public string $period,
        public string $client_name,
        public string $address,
        public string $address_comment,
        public string $address_extra_info,
        public ?string $expected_delivery_time,
        public string $order_comment,
        public float $total,
        /** @var Collection<ItemDTO> $items */
        public Collection $items,
    ) {
    }
}
