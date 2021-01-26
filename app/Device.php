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
        if(isset($this->restrExpiry)){
            return $this->restrExpiry->gt(now());
        }
        return true;
    }

    public function block(){
        $config = Config::first();
        if($config['restrExpiry'] == 0){
            $this->restrExpiry = null;
        }else{
            $this->restrExpiry = now()->addSeconds($config['restrExpiry']);
        }
        $this->save();
    }

    private function getConfig(){
        return Config::first();
    }

    public function ticketsFromTimePeriod(){
        $config = Config::first();
        $from = now()->subSeconds(($config['timeDuration']));
        $ticketsInTimePeriod = $this->tickets()->where('created_at', '>=', $from)->get();
        return $ticketsInTimePeriod;
    }

    public function stakeSumFromPeriod(){
        $stakeSum = 0;
        foreach($this->ticketsFromTimePeriod() as $ticket){
            $stakeSum += $ticket['stake'];
        }
        return $stakeSum;
    }

    public function isAboveLimit(){
        $config = $this->getConfig();
        return $this->stakeSumFromPeriod() >= $config['stakeLimit'];
    }

    public function isHot(){
        $config = $this->getConfig();
        $hotValue = ($config['hotAmountPctg']/100) * $config['stakeLimit'];
        return $this->stakeSumFromPeriod() >= $hotValue;
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
}
