<?php

namespace App\Http\Controllers\API\ERP;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ERP\Brigade\BrigadeRequest;
use App\Services\API\ERP\Brigade\BrigadeImporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class BrigadeController extends Controller
{
    public function __construct(private readonly BrigadeImporter $brigadeService)
    {
    }

    /**
     * receive instances from ERP and repopulates them into database
     */
    public function applyChanges(BrigadeRequest $request): JsonResponse
    {
        Log::info('brigade erp', ['request' => $request->all()]);
        return $this->brigadeService->applyChanges($request->getData())->response();
    }
}
