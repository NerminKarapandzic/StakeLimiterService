<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StakeLimitTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->postJson('/api/checkLimit', [
            "id" => "7d570ef0-0bef-41e9-baea-2535bd08b58f",
            "deviceId" => "7d570ef0-0bef-41e9-baea-2535bd08b58f",
            "stake" => 14
        ]);

        $response->assertStatus(200);
    }
}
