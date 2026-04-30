<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use Symfony\Component\HttpFoundation\Response;

final class DiscrepancyReasonTest extends AdminTestCase
{
    public function testGetAllReturnOk(): void
    {
        //act
        $response = $this->withHeader("Authorization", "Bearer " . $this->token)
            ->actingAs($this->user)
            ->get($this->prefix . '/discrepancy-reasons');

        //assert
        $response->assertStatus(Response::HTTP_OK);
    }
}
