<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Config;
use App\Ticket;

class Device extends Model
{
    protected $fillable = ['id', 'restrExpiry'];
    protected $dates = ['restrExpiry'];
    public $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    public function isBlocked(){
        return $this->restrExpiry->gt(now());
    }

    private function getConfig(){
        return Config::first();
    }

    private function ticketsFromTimePeriod(){
        $config = Config::first();
        $from = now()->subMinutes($config['timeDuration']);
        $ticketsInTimePeriod = $this->tickets()->where('created_at', '>=', $from)->get();
        return $ticketsInTimePeriod;
    }

    private function stakeSumFromPeriod($stake){
        $stakeSum = $stake;
        foreach($this->ticketsFromTimePeriod() as $ticket){
            $stakeSum += $ticket['stake'];
        }
        return $stakeSum;
    }

    public function isAboveLimit($stake){
        $config = $this->getConfig();
        return $this->stakeSumFromPeriod($stake) >= $config['stakeLimit'];
    }

    public function isHot($stake){
        $config = $this->getConfig();
        $hotValue = ($config['hotAmountPctg']/100) * 1000;
        return $this->stakeSumFromPeriod($stake) >= $hotValue;
    }


    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
}
