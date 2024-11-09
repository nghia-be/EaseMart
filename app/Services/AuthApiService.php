<?php

namespace App\Services;
class AuthApiService {
    private $clientKey;
    private $secretKey;
    private $token;
    private $baseUrl;
    private $version;

    public function __construct(){
        $api_config         = config('configDomain.DOMAIN_AUTH.' . env('APP_ENV'));
        $this->baseUrl      = $api_config['URL'];
        $this->clientKey    = $api_config['CLIENT_KEY'];
        $this->secretKey    = $api_config['SECRET_KEY'];
        $this->token        = "$this->clientKey::".md5($this->clientKey . "::" . $this->secretKey . date("Y-d-m"));
    }

    public function reset_delete_user($phone) {
        $url = $this->baseUrl . 'provider/cms/customers/unlock-user';
        $headers = ["Content-Type: Application/json", (!empty($this->token)) ? "Token: " . $this->token : null];
        $response =  $this->sendRequestLocal($url, array('phone' => $phone), null, $headers);
        // $response =  sendRequest($url, array('phone' => $phone), $this->token);
        return $response;
    }

    function sendRequestLocal($url, $params, $token = null, $headerArray = array(),$method = null)
    {
        if(empty($headerArray)) {
            $headers[] = "Content-Type: application/json";
            $headers[] = (!empty($token)) ? "Authorization: " . $token : null;
        }
        else {
            $headers = $headerArray;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds

        // if(env('APP_ENV') !== 'local'){
        //     curl_setopt($ch, CURLOPT_PROXY, 'proxy.hcm.fpt.vn:80');
        //     curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        // }

        $time = microtime(true);
        if(!empty($method)){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }
        $output = curl_exec($ch);
        // dd($output);
        $timeRun = microtime(true) - $time;
        if (curl_errno($ch)) {
            // dd("lỗi .".curl_error($ch));
            // my_debug($url.'</br>'.curl_error($ch));
        }
        curl_close($ch);
        // my_debug($output.'</br>'.$url);
        return json_decode($output);
    }
}