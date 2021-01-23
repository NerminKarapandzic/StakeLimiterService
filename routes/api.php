<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/checkLimit', 'StakeLimitController@index');
Route::post('/config/update', 'ConfigController@update')->name('config.update');