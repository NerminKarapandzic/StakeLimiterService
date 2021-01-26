<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Device;
use App\Ticket;
use App\Config;
use Illuminate\Support\Str;

class DeviceLimitsTest extends TestCase
{
    public function test_success_response()
    {
        $response = $this->postJson('/api/device/check-limits', [
            "id" => "ef65ef3e-d212-3137-7f56-ec533cb7759d",
            "deviceId" => "6c834452-d73f-3e63-9ct6-31372da42078",
            "stake" => 300.99
        ]);

        $response->assertStatus(200);
    }

    public function test_above_stake_limit_response(){
        $config = Config::first();
        $device = factory(Device::class)->create();

        $response = $this->postJson('/api/device/check-limits', [
            "id" => Str::uuid(),
            "deviceId" => $device->id,
            "stake" => $config['stakeLimit']
        ]);

        $response->assertStatus(200)->assertJson([
            "status" => "BLOCKED"
        ]);
    }

    public function test_device_hot(){
        $config = Config::first();
        $device = factory(Device::class)->create();
        $hotAmount = ($config['hotAmountPctg'] / 100) * $config['stakeLimit'];
        
        $response = $this->postJson('/api/device/check-limits', [
            "id" => Str::uuid(),
            "deviceId" => $device->id,
            "stake" => $hotAmount
        ]);

        $response->assertStatus(200)->assertJson([
            "status" => "HOT"
        ]);
    }

    public function test_device_below_all_limits(){
        $config = Config::first();
        $device = factory(Device::class)->create();
        $hotAmount = ($config['hotAmountPctg'] / 100) * $config['stakeLimit'];

        $response = $this->postJson('/api/device/check-limits', [
            "id" => Str::uuid(),
            "deviceId" => $device->id,
            "stake" => $hotAmount - 10
        ]);

        $response->assertStatus(200)->assertJson([
            "status" => "OK"
        ]);
    }

    public function test_device_restriction_time(){
        $config = Config::first();

        $device = factory(Device::class)->create();
        $restrExpiry = $device->restrExpiry;
        $response = $this->postJson('/api/device/check-limits', [
            "id" => Str::uuid(),
            "deviceId" => $device->id,
            "stake" => $config['stakeLimit']
        ]);
        
        $response->assertStatus(200)->assertJson([
            "status" => "BLOCKED"
        ]);
        
        $device = Device::find($device->id);
        $this->assertTrue($device->restrExpiry != $restrExpiry);
    }

    public function test_indefinite_device_blocking(){
        $this->postJson('/api/config/update', [
            "stakeLimit" => 1000,
            "timeDuration" => 500,
            "hotAmountPctg" => 80,
            "restrExpiry" => 0
        ]);
        $config = Config::first();
        $this->assertTrue($config['restrExpiry'] == 0);
        $device = factory(Device::class)->create();
        $response = $this->postJson('/api/device/check-limits', [
            "id" => Str::uuid(),
            "deviceId" => $device->id,
            "stake" => $config['stakeLimit']
        ]);

        $response->assertStatus(200)->assertJson([
            "status" => "BLOCKED"
        ]);
        $device= Device::find($device->id);
        $this->assertTrue($device->restrExpiry == null);
    }
}
