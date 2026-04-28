<?php

namespace App\Http\Resources\Mobile\Order;

use App\Models\Enums\Order\OrderStatus;
use App\Models\Enums\Order\Period;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

final class OrdersResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->sortOrdersByPeriod($this->resource['data']),
        ];
    }

    private function sortOrdersByPeriod(Collection $orders): array
    {
        return [
            'morning' => $this->sortOrdersByStatus($orders->where('period', Period::Morning->value)),
            'day' => $this->sortOrdersByStatus($orders->where('period', Period::Day->value)),
            'evening' => $this->sortOrdersByStatus($orders->where('period', Period::Evening->value)),
        ];
    }

    private function sortOrdersByStatus(Collection $orders): array
    {
        return [
            'list' => OrderResource::collection(
                $orders->whereIn('status', [
                    OrderStatus::Pending->value,
                    OrderStatus::Active->value,
                    OrderStatus::ToBeCanceled->value,
                ])
            ),
            'closedList' => OrderResource::collection(
                $orders->whereIn('status', [
                    OrderStatus::Completed->value,
                    OrderStatus::Canceled->value,
                ])
            ),
        ];
    }

    public function withResponse(Request $request, JsonResponse $response): void
    {
        $response->setStatusCode(Response::HTTP_OK);
    }
}
