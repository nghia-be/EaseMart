<?php

namespace App\Services;

class HdiCustomer
{
    private $clientKey;
    private $secretKey;
    private $token;
    private $baseUrl;
    private $version;

    public function __construct($data = null){
        $api_config         = config('hdi_customer.' . env('APP_ENV'));
        $this->baseUrl      = $api_config['URI_BASE_HI_AUTH'];
        $this->version      = $api_config['HI_AUTH_VERSION'];
        $this->clientKey    = $api_config['HI_CUSTOMER_CLIENT_ID'];
        $this->secretKey    = $api_config['HI_CUSTOMER_SECRET'];
        $this->token        = $this->clientKey . '::' . md5($this->clientKey . "::" . $this->secretKey . date("Y-d-m"));
        // $this->token        = md5($this->clientKey . "::" . $this->secretKey . date("Y-d-m"));
    }

    public function postResetOTPByPhone($params = ['phone' => '']){
        // Call api to reset OTP by phone
        $url = $this->baseUrl . 'provider/cms/customers/reset-limit-otp';
        $result = json_decode($this->sendRequest($url, $params, $this->token), true);

        if(isset($result) && $result['statusCode'] == 0){
            $data['status']     = true;
            $data['data']       = $result['data'];
            $data['message']    = '';
        }
        else{
            $data['status']     = false;
            $data['message']    = (!empty($result['message'])) ? $result['message'] : "Có lỗi trong quá trình reset OTP";
        }
        return $data;
    }

    public function findLikeCode($params = ['supportCode' => '', 'page' => 1]) {
        $url = $this->baseUrl . 'provider/cms/customers/find-like-code'; 
        // $url = 'http://hi-authapi.fpt.vn/' . 'provider/cms/customers/find-like-code';
        $result = json_decode($this->sendRequest($url, $params, $this->token), true);
        // var_dump(json_encode($params));
        // var_dump($result);
        // dd($result);
        return $result;
    }

    public function resetDeviceLockByCode($params = ['supportCode' => '']) {
        $url = $this->baseUrl . 'provider/cms/customers/reset-device-lock-by-code';
        $result = json_decode($this->sendRequest($url, $params, $this->token), true);
        return $result;
    }

    public function sendRequest($url, $params, $token = null){
        $headers[] = "Content-Type: application/json";
        $headers[] = (!empty($token)) ? "Token: " . $token : null;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds

        $time = microtime(true);
        $output = curl_exec($ch);
        $timeRun = microtime(true) - $time;
        // if (curl_errno($ch)) {
            // my_debug($url.'</br>'.curl_error($ch));
        // }
        curl_close($ch);
        return $output;
    }
}
