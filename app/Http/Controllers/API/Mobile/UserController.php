<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Requests\API\Admin\User\AuthRequest;
use App\Http\Requests\API\Mobile\User\AddTokenRequest;
use App\Http\Requests\API\Mobile\User\RemoveTokenRequest;
use App\Services\API\Mobile\User\Auth\AuthServiceInterface;
use App\Services\API\Mobile\User\Token\TokenManagerInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

final class UserController extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    public function __construct(
        private readonly AuthServiceInterface $authService,
        private readonly TokenManagerInterface $tokenManager
    ) {
    }

    /**
     *  Authorize user
     */
    public function auth(AuthRequest $request): JsonResponse
    {
        return $this->authService->auth($request->toDTO())->response();
    }

    /**
     * By this method, fronted can know whether user is authorized or not
     */
    public function checkAuth(): JsonResponse
    {
        return $this->authService->checkAuth()->response();
    }

    public function logout(): JsonResponse
    {
        return $this->authService->logout()->response();
    }

    /**
     * Add new FCM token, so user can receive push notifications
     */
    public function addToken(AddTokenRequest $request): JsonResponse
    {
        return $this->tokenManager->addToken($request->toDTO())->response();
    }

    /**
     * remove FCM token. User CAN be unauthorized to perform this action
     */
    public function removeToken(RemoveTokenRequest $request): JsonResponse
    {
        return $this->tokenManager->removeToken($request->toDTO())->response();
    }
}
