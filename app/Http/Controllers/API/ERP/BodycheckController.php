<?php

namespace App\Http\Controllers\API\ERP;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ERP\Bodycheck\BodycheckRequest;
use App\Services\API\ERP\Bodycheck\BodycheckImporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class BodycheckController extends Controller
{
    public function __construct(private readonly BodycheckImporter $bodycheckService)
    {
    }

    /**
     * receive instances from ERP and repopulates them into database
     */
    public function applyChanges(BodycheckRequest $request): JsonResponse
    {
        Log::info('bodycheck erp', ['request' => $request->all()]);
        return $this->bodycheckService->applyChanges($request->getData())->response();
    }
}
