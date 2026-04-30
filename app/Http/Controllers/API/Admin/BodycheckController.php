<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\API\Admin\BodycheckService;
use Illuminate\Http\JsonResponse;

final class BodycheckController extends Controller
{
    public function __construct(private readonly BodycheckService $bodycheckService)
    {
    }

    /**
     *  get all records paginated
     */
    public function getAll(): JsonResponse
    {
        return $this->bodycheckService->getAll()->response();
    }
}
