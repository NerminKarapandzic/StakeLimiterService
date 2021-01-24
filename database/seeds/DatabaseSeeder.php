<?php

use Illuminate\Database\Seeder;
use App\Device;
use App\Ticket;
use App\Config;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(Device::class, 50)->create();
        factory(Ticket::class, 300)->create();
        DB::table('configs')->insert([
            'timeDuration' => Config::defaultTimeDuration(),
            'stakeLimit' => Config::defaultStakeLimit(),
            'hotAmountPctg' => Config::defaultHotAmountPercentage(),
            'restrExpiry' => Config::defaultRestrExpiry()
        ]);

        //Devices are created with expiry set to now(), check all devices after tickets are created and block the ones which need to be blocked
        $devices = Device::all();
        foreach($devices as $device){
            if($device->isAboveLimit()){
                $device->block();
            }
        }
    }
}
