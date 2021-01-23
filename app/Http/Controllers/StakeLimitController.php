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
            $device = Device::create([
                'id' => $request['deviceId'],
                'restrExpiry' => now()    
            ]);
        }

        //If device restrExpiry is more than current time, device is still blocked
        if($device->isBlocked()){
            return $this->sendResponse('BLOCKED');
        }else{
            //device restrExpiry is null or less than current time, check sum of stakes received in last {config time}
            //if stake sum >= {config limit} block the device
            if($device->isAboveLimit($request['stake'])){
                return $this->sendResponse('BLOCKED');
            }else if($device->isHot($request['stake'])){
                //stake sum >= {betAmountPctg} status hot, ticket won't be rejected so we store this ticket for further tracking
                $this->storeTicket($ticketMessage);
                return $this->sendResponse('HOT');
            }else{
                //else status OK 
                $this->storeTicket($ticketMessage);
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

    private function sendResponse($status){
        return response()->json(["status" => $status], 200);
    }
}
