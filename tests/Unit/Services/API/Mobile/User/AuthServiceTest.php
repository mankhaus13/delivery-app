<?php

namespace Tests\Unit\Services\API\Mobile\User;

use App\DTO\API\Mobile\User\AuthDTO;
use App\Http\Resources\Mobile\User\AuthenticatedResource;
use App\Http\Resources\Mobile\User\UnauthorizedResource;
use App\Services\API\Mobile\User\Auth\AuthService;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    private readonly AuthService $authService;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->authService = new AuthService();
    }

    public function testAuthWithValidCredentials(): void
    {
        // Arrange
        $authDTO = new AuthDTO(
            '81234567898',
            'demodemo',
        );

        // Act
        $result = $this->authService->auth($authDTO);

        // Assert
        $this->assertInstanceOf(AuthenticatedResource::class, $result);
    }

    public function testAuthWithInvalidCredentials(): void
    {
        // Arrange
        $authDTO = new AuthDTO(
            '123',
            '231213',
        );

        // Act
        $result = $this->authService->auth($authDTO);

        // Assert
        $this->assertInstanceOf(UnauthorizedResource::class, $result);
    }

    public function testCheckAuthWhenUnauthenticated()
    {
        $result = $this->authService->checkAuth();
        $this->assertInstanceOf(UnauthorizedResource::class, $result);
    }

    public function testCheckAuthWhenAuthenticated()
    {
        // Arrange
        $authDTO = new AuthDTO(
            '81234567898',
            'demodemo',
        );
        $this->authService->auth($authDTO);

        // Act
        $result = $this->authService->checkAuth();

        //assert
        $this->assertInstanceOf(AuthenticatedResource::class, $result);
    }
}
