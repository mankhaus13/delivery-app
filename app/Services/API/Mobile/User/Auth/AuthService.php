<?php

declare(strict_types=1);

namespace App\Services\API\Mobile\User\Auth;

use App\DTO\API\Mobile\User\AuthDTO;
use App\Http\Resources\Mobile\User\AuthenticatedResource;
use App\Http\Resources\Mobile\User\LogoutResource;
use App\Http\Resources\Mobile\User\UnauthorizedResource;
use App\Models\Enums\User\ActionsToLog;
use App\Traits\Logger;
use Illuminate\Support\Facades\Auth;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class AuthService implements AuthServiceInterface
{
    use Logger;

    public function auth(AuthDTO $authDTO): UnauthorizedResource|AuthenticatedResource
    {
        $authSucceeded = Auth::attempt([
            'phone' => $authDTO->phone,
            'password' => $authDTO->password,
        ]);

        if (! $authSucceeded) {
            return new UnauthorizedResource([]);
        }
        $userId = (int) Auth::id();
        $this->logAction(
            action: ActionsToLog::Auth,
            userId: $userId,
        );

        return new AuthenticatedResource(['id' => $userId]);
    }

    public function checkAuth(): UnauthorizedResource|AuthenticatedResource
    {
        if (! Auth::check()) {
            return new UnauthorizedResource([]);
        }

        return new AuthenticatedResource(['id' => Auth::id()]);
    }

    public function logout(): LogoutResource
    {
        Auth::logout();
        return new LogoutResource([]);
    }
}
