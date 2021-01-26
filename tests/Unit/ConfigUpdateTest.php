<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Config;
use Illuminate\Support\Str;

class ConfigUpdateTest extends TestCase
{
   private $configUrl = '/api/config/update';
    public function test_config_response()
    {
        $response = $this->postJson($this->configUrl, [
            "stakeLimit" => 1000,
            "timeDuration" => 500,
            "hotAmountPctg" => 80,
            "restrExpiry" => 1800
        ]);

        $response->assertStatus(200);
    }

    public function test_config_validation_errors(){
        $response = $this->postJson($this->configUrl, [
            "stakeLimit" => 0,
            "timeDuration" => 0,
            "hotAmountPctg" => 0,
            "restrExpiry" => "s"
        ]);

        $response->assertStatus(422)->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "stakeLimit" => [
                    "The stake limit must be at least 1."
                ],
                "timeDuration" => [
                    "The time duration must be at least 300."
                ],
                "hotAmountPctg" => [
                    "The hot amount pctg must be at least 1."
                ],
                "restrExpiry" => [
                    "The restr expiry must be a number."
                ]
            ]
        ]);
    }
}
