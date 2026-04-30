<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Admin\User\AuthRequest;
use App\Http\Requests\API\Admin\User\CreateUserRequest;
use App\Http\Requests\API\Admin\User\DeleteUserRequest;
use App\Http\Requests\API\Admin\User\EditUserRequest;
use App\Http\Requests\API\Admin\User\GetLogRequest;
use App\Services\API\Admin\UserService;
use App\Services\API\Mobile\User\Auth\AuthService as MobileUserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly MobileUserService $mobileUserService
    ) {
    }

    /**
     * get all records paginated
     */
    public function getAll(): JsonResponse
    {
        return $this->userService->getAll()->response();
    }

    /**
     * create new expeditor
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        return $this->userService->create($request->toDTO())->response();
    }

    /**
     * edit expeditor's data
     */
    public function edit(EditUserRequest $request): JsonResponse
    {
        return $this->userService->edit($request->toDTO())->response();
    }

    /**
     * delete expeditor
     */
    public function delete(DeleteUserRequest $request): JsonResponse
    {
        return $this->userService->delete($request->toDTO())->response();
    }

    /**
     * authorize in admin panel
     */
    public function auth(AuthRequest $request): JsonResponse
    {
        return $this->mobileUserService->auth($request->toDTO())->response();
    }

    /**
     * by this method frontend can check whether it is authorized or not
     */
    public function checkAuth(): JsonResponse
    {
        return $this->mobileUserService->checkAuth()->response();
    }

    /**
     * get file with log records being stored in database
     * logs contains info about user performing certain action, such as authorization, start order, cancel order, etc
     */
    public function getLogs(GetLogRequest $request): BinaryFileResponse
    {
        [$file, $fileName, $headers] = $this->userService->getLogs($request->toDTO());

        return response()->download($file, $fileName, $headers);
    }
}
