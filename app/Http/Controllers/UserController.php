<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserReq;
use App\SmsCode;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends FileController
{

    public function register(Request $req){

        try{

            $validate = Validator::make($req->all(),[
                'name' => ['required'],
                'cellphone' => ['required', 'min:10', 'max:10', 'unique:users'],
                'city' => ['required'],
                'region' => ['required'],
                'password' => ['required', 'min:8', 'max:16'],
                'role' => ['required']
            ]);

            if ($validate->fails()) {
                return response()->json($validate->messages(), 200);
            }

            $user = new User();
            $user->name = $req->name;
            $user->cellphone = $req->cellphone;
            $user->city = $req->city;
            $user->region = $req->region;
            $user->password = bcrypt($req->password);
            $user->role = $req->role;
            $user->status = 0;
            $user->profile = 'default.png';
            $user->slug = Str::random(15);
            $user->save();

            $sms_obj = new smsCodeController();
            $send = $sms_obj->sendCode($req->cellphone);

            Auth::loginUsingId($user->id);
            if($send == 1)
                return response()->json(['status' => 1,'msg' => 'کد تائید شماره همراه ارسال شد']);
            else
                return response()->json(['status' => 0,'msg' => 'خطا در ارسال کد به شماره همراه']);

        } catch (\Exception $exp){
            return response()->json(['status' => 0,'msg' => $this->fails_msg]);
        }

    }

    public function changeStatus(Request $req){

        $user = User::findOrFail($req->user_id);

        try{

            $user->status = $req->status;
            $user->save();
            return response()->json(['status' => 1,'msg' => $this->success_msg]);

        } catch (\Exception $exc) {

            return response()->json(['status' => 0,'msg' => $this->fails_msg]);

        }
    }

    public function checkCode($code,$mobile){

        $check = SmsCode::where('mobile',$mobile)->where('code',$code)->where('expire_at','>',Carbon::now())->where('status',0)->first();
        if(is_null($check))
            return response()->json(['status' => 0,'msg' => 'کد احراز هویت پیدا نشد']);

        try{

            $user = User::where('cellphone',$mobile)->first();
            $user->status = 1;
            $user->save();
            $check->status = 1;
            $check->save();

            return response()->json(['status' => 1,'msg' => 'حساب شما با موفقیت فعال شد']);

        } catch (\Exception $exp){
            return response()->json(['status' => 0,'msg' => $this->fails_msg]);
        }

    }

    public function checkExist($mobile){

        if(is_null(User::where('cellphone',$mobile)->first()))
            return response()->json(['status' => 0,'msg' => $this->empty_result]);
        else
            return response()->json(['status' => 1,'msg' => 'شماره مورد نظر قبلا در سیستم ثبت شده است']);

    }

    public function updatePresence(Request $req){

        $user = User::FindOrFail($req->user_id);
        $user->is_onilne = $req->status;
        if($user->save())
            return response()->json(['status' => 1,'msg' => $this->success_msg]);
        else
            return response()->json(['status' => 0,'msg' => $this->fails_msg]);

    }

}
