<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Config;
use App\Http\Requests\UpdateConfigRequest;

class ConfigController extends Controller
{
    public function update(UpdateConfigRequest $request){
        
        $data = $request->only(['stakeLimit', 'timeDuration', 'restrExpiry', 'hotAmountPctg']);
        $config = Config::first();
        $config->update($data);
       
        //If request came from dashboard
        if(!$request->wantsJson()){
            return redirect('/');
        }

        return response()->json('Configuration updated.');
    }
}
