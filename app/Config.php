<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    private const DEFAULT_TIME_DURATION = 5;
    private const DEFAULT_STAKE_LIMIT = 1000;
    private const DEFAULT_HOT_AMOUNT_PERCENTAGE = 80;

    public static function defaultTimeDuration(){
        return self::DEFAULT_TIME_DURATION;
    }

    public static function defaultStakeLimit(){
        return self::DEFAULT_STAKE_LIMIT;
    }

    public static function defaultHotAmountPercentage(){
        return self::DEFAULT_HOT_AMOUNT_PERCENTAGE;
    }
}
