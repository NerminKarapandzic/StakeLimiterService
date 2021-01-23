<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\Ticket;
use App\Config;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard')->with(['devices' => Device::paginate(10)])->with(['config' => Config::first()]);
    }
}
