<?php

namespace App\Http\Resources\Mobile\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActiveOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'order' => [
                'id' => $this->id,
                'status' => $this->status,
                'number' => $this->number,
                'userName' => $this->client_name,
                'intervalLabel' => $this->interval_label,
                'addressLabel' => $this->address,
                'addressAdditionalLabel' => $this->address_extra_info,
                'totalAmount' => $this->total,
                'period' => $this->period,
                'emptyBottles' => $this->return_bottles,
                'addressComment' => $this->address_comment,
                'comment' => $this->order_comment,
                'paymentType' => $this->payment_method,
                'timeToWait' => $this->wait_until,
                'items' => ItemResource::collection($this->whenLoaded('items')),
            ],
        ];
    }
}
