<?php

namespace Tests\Feature;

use App\Models\Enums\User\UserRole;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Config;

class ApiAuthTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testTheApplicationRedirectsUnauthorizedUsers(): void
    {
        //act
        $response = $this->get('/api/mobile/active-order');

        //assert
        $response->assertRedirect('/api/unauth');
        $response->assertRedirectToRoute('login');
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function testApiAccessIsRestricted(): void
    {
        //arrange
        /** @var User $user */
        $user = User::query()->where('role', UserRole::Expeditor->value)->first();

        //act
        $response = $this->actingAs($user)->get('/api/mobile/active-order');

        //assert
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testApiAccessWithWrongToken(): void
    {
        //arrange
        /** @var User $user */
        $user = User::query()->where('role', UserRole::Expeditor->value)->first();

        //act
        $response = $this->withHeader("Authorization", "Bearer " . "dummy_string")
            ->actingAs($user)
            ->get('/api/mobile/active-order');

        //assert
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testApiAccessAuthorized(): void
    {
        //arrange
        /** @var User $user */
        $user = User::query()->where('role', UserRole::Expeditor->value)->first();
        $validTokens = Config::get('api.tokens');
        $token = $validTokens[array_rand($validTokens)];

        //act
        $response = $this->withHeader("Authorization", "Bearer " . $token)
            ->actingAs($user)
            ->get('/api/mobile/active-order');

        //assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testMobileAccessRestrictedForAdmin(): void
    {
        //arrange
        /** @var User $user */
        $user = User::query()->where('role', UserRole::Admin->value)->first();
        $validTokens = Config::get('api.tokens');
        $token = $validTokens[array_rand($validTokens)];

        //act
        $response = $this->withHeader("Authorization", "Bearer " . $token)
            ->actingAs($user)
            ->get('/api/mobile/active-order');

        //assert
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testAdminAccessRestrictedForExpeditors(): void
    {
        //arrange
        /** @var User $user */
        $user = User::query()->where('role', UserRole::Expeditor->value)->first();
        $validTokens = Config::get('api.tokens');
        $token = $validTokens[array_rand($validTokens)];

        //act
        $response = $this->withHeader("Authorization", "Bearer " . $token)
            ->actingAs($user)
            ->get('/api/admin/bodychecks');

        //assert
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
