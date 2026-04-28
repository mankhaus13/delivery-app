<?php

declare(strict_types=1);

namespace Tests\Feature\Mobile;

use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;

final class OrderTest extends MobileTestCase
{
    public function testGetAllReturnOk(): void
    {
        //act
        $response = $this->withHeader("Authorization", "Bearer " . $this->token)
            ->actingAs($this->user)
            ->get($this->prefix . '/orders?date=' . date('d.m.Y'));

        //assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(
            fn(AssertableJson $json) => $json->hasAll(['data', 'message'])
        );
    }

    public function getActiveOrderReturnsOk(): void
    {
        //act
        $response = $this->withHeader("Authorization", "Bearer " . $this->token)
            ->actingAs($this->user)
            ->get($this->prefix . '/active-order');

        //assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(
            fn(AssertableJson $json) => $json->hasAll(['data', 'message'])
        );
    }
}
