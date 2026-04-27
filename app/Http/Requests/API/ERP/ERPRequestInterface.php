<?php

namespace App\Http\Requests\API\ERP;

use Illuminate\Support\Collection;

interface ERPRequestInterface
{
    public function getData(): array|Collection;
}
