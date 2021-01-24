<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = ['stakeLimit', 'timeDuration', 'restrExpiry', 'hotAmountPctg'];
    public $timestamps = false;
    private const DEFAULT_TIME_DURATION = 30*60; //seconds
    private const DEFAULT_STAKE_LIMIT = 1000;
    private const DEFAULT_HOT_AMOUNT_PERCENTAGE = 80; 
    private const DEFAULT_RESTR_EXPIRY = 5*60; //seconds

    public static function defaultTimeDuration(){
        return self::DEFAULT_TIME_DURATION;
    }

    public static function defaultStakeLimit(){
        return self::DEFAULT_STAKE_LIMIT;
    }

    public static function defaultHotAmountPercentage(){
        return self::DEFAULT_HOT_AMOUNT_PERCENTAGE;
    }

    public static function defaultRestrExpiry(){
        return self::DEFAULT_RESTR_EXPIRY;
    }
}
