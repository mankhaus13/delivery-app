<?php

namespace App\Http\Resources\Mobile\Calendar;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

final class CalendarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->resource;
    }

    public function withResponse(Request $request, JsonResponse $response): void
    {
        $response->setStatusCode(Response::HTTP_OK);
    }
}
