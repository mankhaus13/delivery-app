<?php

namespace App\Services\API\ERP;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

interface ERPServiceInterface
{
    public function applyChanges(Collection $records): JsonResource;
}
