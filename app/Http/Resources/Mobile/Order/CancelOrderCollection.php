<?php

namespace App\Http\Resources\Mobile\Order;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CancelOrderCollection extends ResourceCollection
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
            'message' => 'Запрос отправлен менеджеру',
        ];
    }
}
