<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['id', 'device_id', 'stake'];
    public $primaryKey = 'id';
    public $incrementing = false;
}
