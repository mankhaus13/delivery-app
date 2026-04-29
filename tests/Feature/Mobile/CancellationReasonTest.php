<?php

declare(strict_types=1);

namespace Tests\Feature\Mobile;

use Symfony\Component\HttpFoundation\Response;

final class CancellationReasonTest extends MobileTestCase
{
    public function testGetAllReturnOk(): void
    {
        //act
        $response = $this->withHeader("Authorization", "Bearer " . $this->token)
            ->actingAs($this->user)
            ->get($this->prefix . '/cancel-reasons');

        //assert
        $response->assertStatus(Response::HTTP_OK);
    }
}
