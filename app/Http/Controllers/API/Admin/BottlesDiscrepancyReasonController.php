<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Admin\BottlesDiscrepancyReason\CreateRequest;
use App\Http\Requests\API\Admin\BottlesDiscrepancyReason\DeleteRequest;
use App\Http\Requests\API\Admin\BottlesDiscrepancyReason\EditRequest;
use App\Services\API\Admin\BottlesDiscrepancyReasonService;
use Illuminate\Http\JsonResponse;

final class BottlesDiscrepancyReasonController extends Controller
{
    public function __construct(private readonly BottlesDiscrepancyReasonService $service)
    {
    }

    /**
     *  get all records paginated
     */
    public function getAll(): JsonResponse
    {
        return $this->service->getAll()->response();
    }

    /**
     * create new reason
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return $this->service->create($request->toDTO())->response();
    }

    /**
     * edit reason
     */
    public function edit(EditRequest $request): JsonResponse
    {
        return $this->service->edit($request->toDTO())->response();
    }

    /**
     * delete reason
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        return $this->service->delete($request->toDTO())->response();
    }
}
