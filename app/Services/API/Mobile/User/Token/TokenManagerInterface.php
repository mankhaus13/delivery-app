<?php

namespace App\Services\API\Mobile\User\Token;

use App\DTO\API\Mobile\User\AddTokenDTO;
use App\DTO\API\Mobile\User\RemoveTokenDTO;
use Illuminate\Http\Resources\Json\JsonResource;

interface TokenManagerInterface
{
    public function addToken(AddTokenDTO $dto): JsonResource;

    public function removeToken(RemoveTokenDTO $dto): JsonResource;
}
