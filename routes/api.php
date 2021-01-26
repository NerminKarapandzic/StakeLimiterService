<?php

use Illuminate\Support\Facades\Route;


Route::post('/device/check-limits', 'StakeLimitController@index');
Route::post('/config/update', 'ConfigController@update')->name('config.update');