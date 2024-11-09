<?php

namespace App\Http\Controllers\SmsWorld;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\MY_Controller;
use App\Services\SmsWorldService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class OtpController extends BaseController
{
    //
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Sms World';
    }
    public function logs(Request $request){
        $rules  = [
            'phone' => 'required',
            'date' => 'required'
        ];
        $params = $request->all();
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            return view('smsworld.logs');
        };

        $this->addToLog($request);
        $resultData = $this->getLog($params);
        if(isset($resultData->error)){
            return redirect()->route('smsworld.logs')->withErrors($resultData->error);
        }
        return view('smsworld.logs')->with(['data'=>$resultData]);
    }
    private function convertPhone($phone){
        if($phone[0] === '0'){
            $phone = preg_replace('/' . '0' . '/', '84', $phone, 1);
        }
        return $phone;
    }
    public function getLog($params){
        $result = [];
        $userName = 'hifpt';
        $passWord = 'a1a3ccf8678d7524129470a0ec47eb5a';
        $smsWorldService = new SmsWorldService;
        $response_Login = $smsWorldService->login($userName,$passWord);
        if(empty($response_Login->Detail)){
            $result['error'] = 'Authorization has been denied for this request.';
        }else{
            $accessToken = $response_Login->Detail->AccessToken;
            if($accessToken === false){
                $result['error'] = 'Authorization has been denied for this request.';
            }else{
                $smsWorldService = new SmsWorldService;
                $params['PhoneNumber'] = $this->convertPhone($params['phone']);
                $timestamp = strtotime($params['date']);
                $params['Month'] = date('m',$timestamp);
                $params['Year']  = date('Y',$timestamp);
                $response = $smsWorldService->getlogs($params['PhoneNumber'], $params['Month'], $params['Year'], $accessToken);
                if(empty($response->Detail)){
                    $result['error'] = $response->Message;
                }else{
                    $result = $response->Detail;
                }
            }
        }
        return $result;
    }

    private function getAccessToken(){
        $keyName = config('constants.REDIS_KEY.ACCESS_TOKEN_SMS_WORLD');
        $accessToken = Redis::get($keyName);
        return (is_null($accessToken)) ? false: unserialize($accessToken);
    }

    private function setAccessToken($accessToken){
        $keyName = config('constants.REDIS_KEY.ACCESS_TOKEN_SMS_WORLD');
        Redis::setex($keyName,1000, serialize($accessToken));
        return true;
    }
}
