<?php

declare(strict_types=1);

namespace Tests\Feature\Mobile;

use App\Models\Enums\User\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class MobileTestCase extends TestCase
{
    protected User $user;
    protected string $token;
    protected string $prefix = '/api/mobile';

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::query()->where('role', UserRole::Expeditor->value)->first();

        $validTokens = Config::get('api.tokens');
        $this->token = $validTokens[array_rand($validTokens)];
    }
}
