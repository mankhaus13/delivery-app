<?php

declare(strict_types=1);

namespace App\Services\API\Mobile\User\Token;

use App\DTO\API\Mobile\User\AddTokenDTO;
use App\DTO\API\Mobile\User\RemoveTokenDTO;
use App\Http\Resources\Mobile\User\AddTokenResource;
use App\Http\Resources\Mobile\User\RemoveTokenResource;
use App\Models\User;

final readonly class TokenManagerService implements TokenManagerInterface
{
    public function addToken(AddTokenDTO $dto): AddTokenResource
    {
        User::query()
            ->where('id', $dto->expeditorId)
            ->update(['device_token' => $dto->token]);

        return new AddTokenResource([]);
    }

    public function removeToken(RemoveTokenDTO $dto): RemoveTokenResource
    {
        User::query()
            //юзер может быть неавторизованным на момент удаления токена
            ->where('device_token', $dto->token)
            ->update(['device_token' => null]);

        return new RemoveTokenResource([]);
    }
}
