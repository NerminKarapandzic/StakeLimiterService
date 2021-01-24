<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'DashboardController@index');
Route::get('/device/{device}', 'DashboardController@device')->name('device.details');
Route::post('/device/{device}/addTicket', 'DashboardController@addTicket')->name('add.ticket');
