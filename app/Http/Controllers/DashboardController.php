<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\Ticket;
use App\Config;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard')->with(['devices' => Device::paginate(10)])->with(['config' => Config::first()]);
    }

    public function device(Device $device){
        $tickets = $device->tickets()->orderBy('created_at', 'desc')->paginate(10);
        return view('device')->with(['device' => $device])->with(['tickets' => $tickets])->with(['config'=> Config::first()]);
    }

    public function addTicket(Device $device, Request $request){
        if(!$device->isBlocked()){
            $ticket = $device->tickets()->create([
                'id' => Str::uuid(),
                'stake' => $request->stake
            ]);
            if($device->isAboveLimit()){
                $device->block();
            }
            return redirect()->back();
        }else{
            return redirect()->back();
        }
    }
}
