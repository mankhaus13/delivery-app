<?php

namespace App\Http\Requests\API;

use App\DTO\API\BaseAPIDTO;

interface ApiRequestInterface
{
    public function toDTO(): BaseAPIDTO;
}
