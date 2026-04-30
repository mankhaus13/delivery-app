<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use Symfony\Component\HttpFoundation\Response;

final class UserTest extends AdminTestCase
{
    public function testGetAllReturnOk(): void
    {
        //act
        $response = $this->withHeader("Authorization", "Bearer " . $this->token)
            ->actingAs($this->user)
            ->get($this->prefix . '/users');

        //assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function getLogDownloads()
    {
        //act
        $response = $this->withHeader("Authorization", "Bearer " . $this->token)
            ->actingAs($this->user)
            ->get($this->prefix . '/user/logs');

        //assert
        $response->assertDownload();
    }
}
