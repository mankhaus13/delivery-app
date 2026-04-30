<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\API\Admin\ItemService;
use Illuminate\Http\JsonResponse;

final class ItemController extends Controller
{
    public function __construct(private readonly ItemService $itemService)
    {
    }

    /**
     * get all records paginated
     */
    public function getAll(): JsonResponse
    {
        return $this->itemService->getAll()->response();
    }
}
