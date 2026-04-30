<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\API\Admin\BrigadeService;
use Illuminate\Http\JsonResponse;

final class BrigadeController extends Controller
{
    public function __construct(private readonly BrigadeService $brigadeService)
    {
    }

    /**
     *  get all records paginated
     */
    public function getAll(): JsonResponse
    {
        return $this->brigadeService->getAll()->response();
    }
}
