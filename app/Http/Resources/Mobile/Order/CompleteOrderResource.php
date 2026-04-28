<?php

namespace App\Http\Resources\Mobile\Order;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class CompleteOrderResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [];
    }

    public function withResponse(Request $request, JsonResponse $response): void
    {
        $response->setStatusCode(Response::HTTP_OK);
    }

    public function with(Request $request): array
    {
        return [
            'message' => $this->resource['message'],
        ];
    }
}
