<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use Symfony\Component\HttpFoundation\Response;

final class BrigadeTest extends AdminTestCase
{
    public function testGetAllReturnOk(): void
    {
        //act
        $response = $this->withHeader("Authorization", "Bearer " . $this->token)
            ->actingAs($this->user)
            ->get($this->prefix . '/brigades');

        //assert
        $response->assertStatus(Response::HTTP_OK);
    }
}
