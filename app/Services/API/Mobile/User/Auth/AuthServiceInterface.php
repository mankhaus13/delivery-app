<?php

namespace App\Services\API\Mobile\User\Auth;

use App\DTO\API\Mobile\User\AuthDTO;
use Illuminate\Http\Resources\Json\JsonResource;

interface AuthServiceInterface
{
    public function auth(AuthDTO $authDTO): JsonResource;

    public function checkAuth(): JsonResource;

    public function logout(): JsonResource;
}
