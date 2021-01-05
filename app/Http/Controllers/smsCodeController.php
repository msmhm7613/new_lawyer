<?php

namespace App\Http\Controllers;

use App\smsCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class smsCodeController extends FileController
{
    public function sendCode($mobile){

        try{

            // generate code
            $code = rand(100000,999999);

            // create expire_at
            $expire = Carbon::now()->addMinutes(10);
            SmsCode::create([
                'code' => $code,
                'mobile' => $mobile,
                'expire_at' => $expire,
                'status' => 0
            ]);

            // send sms code
            return 1;

        } catch (\Exception $exp){
            return 0;
        }

    }
}
