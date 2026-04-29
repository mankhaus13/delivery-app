<?php

declare(strict_types=1);

namespace App\Http\Resources\Mobile\BottlesDiscrepancyReason;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class BottlesDiscrepancyReasonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->reason,
        ];
    }
}
