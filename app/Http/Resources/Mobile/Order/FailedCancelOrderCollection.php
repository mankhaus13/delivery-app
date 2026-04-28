<?php

namespace App\Http\Resources\Mobile\Order;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class FailedCancelOrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [],
        ];
    }

    public function withResponse(Request $request, JsonResponse $response): void
    {
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public function with(Request $request): array
    {
        return [
            'message' => 'Не удалось отменить заказ',
        ];
    }
}
