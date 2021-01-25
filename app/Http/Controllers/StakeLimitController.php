<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\Ticket;

class StakeLimitController extends Controller
{
    public function index(Request $request){
        $ticketMessage = $request->all();

        //Find the device from the request
        $device = Device::find($request['deviceId']);
        //If doesn't exist add new device to database so it can be tracked in future
        if(!$device){
           $device = $this->storeNewDevice($request['deviceId']);
        }

        //If device restrExpiry is more than current time, device is still blocked
        if($device->isBlocked()){
            return $this->sendResponse('BLOCKED');
        }else{
            //device is not already blocked, save the ticket for future tracking
            $this->storeTicket($ticketMessage);
            //check the stake sum in last {config time_duraton}
            //if stake sum >= {config limit} block the device
            if($device->isAboveLimit()){
                $device->block();
                return $this->sendResponse('BLOCKED');
            }else if($device->isHot()){
                return $this->sendResponse('HOT');
            }else{
                return $this->sendResponse('OK');
            }
        }
    }

    private function storeTicket($ticket){
        $ticketExists = Ticket::find($ticket['id']);
        if(!$ticketExists){
            Ticket::create([
                'id' => $ticket['id'],
                'device_id' => $ticket['deviceId'],
                'stake' => (float)$ticket['stake']
            ]);
        }
    }

    private function storeNewDevice($deviceId){
        $device = Device::create([
            'id' => $deviceId,
            'restrExpiry' => now()    
        ]);
        return $device;
    }

    private function sendResponse($status){
        return response()->json(["status" => $status], 200);
    }
}
