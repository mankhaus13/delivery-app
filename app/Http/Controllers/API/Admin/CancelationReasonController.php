<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Admin\CancelationReason\CreateCancelationReasonRequest;
use App\Http\Requests\API\Admin\CancelationReason\DeleteCancelationReasonRequest;
use App\Http\Requests\API\Admin\CancelationReason\EditCancelationReasonRequest;
use App\Services\API\Admin\CancelationReasonService;
use Illuminate\Http\JsonResponse;

final class CancelationReasonController extends Controller
{
    public function __construct(private readonly CancelationReasonService $service)
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
     * Create new reason why an order can be canceled and send mqtt packet to all users
     */
    public function create(CreateCancelationReasonRequest $request): JsonResponse
    {
        return $this->service->create($request->toDTO())->response();
    }

    /**
     * Edit existing reason
     */
    public function edit(EditCancelationReasonRequest $request): JsonResponse
    {
        return $this->service->edit($request->toDTO())->response();
    }

    /**
     * Delete existing reason
     */
    public function delete(DeleteCancelationReasonRequest $request): JsonResponse
    {
        return $this->service->delete($request->toDTO())->response();
    }
}
