<?php

namespace Tests\Unit\Services\API\Mobile\User;

use App\DTO\API\Mobile\User\AddTokenDTO;
use App\DTO\API\Mobile\User\RemoveTokenDTO;
use App\Http\Resources\Mobile\User\AddTokenResource;
use App\Http\Resources\Mobile\User\RemoveTokenResource;
use App\Models\User;
use App\Services\API\Mobile\User\Token\TokenManagerService;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private readonly TokenManagerService $service;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->service = new TokenManagerService();
    }

    public function testRemoveToken()
    {
        //Arrange
        /** @var User $user */
        $user = User::factory(1)->create()->first();
        $token = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $dto = new AddTokenDTO(token: $token, expeditorId: $user->id);

        //act
        $result = $this->service->addToken($dto);

        //assert
        $this->assertInstanceOf(AddTokenResource::class, $result);
        $this->assertEquals($user->refresh()->getDeviceToken(), $token);
    }

    public function testAddToken()
    {
        //Arrange
        /** @var User $user */
        $user = User::factory(1)->create()->first();
        $token = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $dto = new AddTokenDTO(token: $token, expeditorId: $user->id);
        $this->service->addToken($dto);

        $token = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $dto = new RemoveTokenDTO(token: $token);

        //act
        $result = $this->service->removeToken($dto);

        //assert
        $this->assertInstanceOf(RemoveTokenResource::class, $result);
        $this->assertEquals(expected: null, actual: $user->refresh()->getDeviceToken());
    }
}
