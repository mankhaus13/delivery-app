<?php

namespace App\Http\Resources\Mobile\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'image' => $this->image,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'type' => $this->type,
        ];
    }
}
