<?php

namespace App\Services;
class ApiService
{
    public function hidePayment($param) {
        $api_config     = config('configDomain.DOMAIN_CUSTOMER.' . env('APP_ENV'));
        // dd($api_config);
        $baseUrl        = $api_config['URL'];
        $clientKey      = $api_config['CLIENT_KEY'];
        $subDomain      = (!empty($api_config['SUB_DOMAIN'])) ? implode('/', $api_config['SUB_DOMAIN']) . '/' : ''; 
        $secretKey      = $api_config['SECRET_KEY'];
        $token          = md5($clientKey . "::" . $secretKey . date("Y-d-m"));
        // dd($token);
        // dd($clientKey . "::" . $secretKey . date("Y-d-m"));
        
        $url = $baseUrl . $subDomain . 'change-config-version';
        $result_raw = sendRequest($url, $param, $token);
        
        $result = (!is_int($result_raw) && !is_string($result_raw)) ? json_decode(json_encode($result_raw), true) : $result_raw;
        if(isset($result['statusCode']) && $result['statusCode'] == 0){
            $data['status']     = true;
            $data['data']       = (!empty($result['data'])) ? $result['data'] : '';
            $data['message']    = (!empty($result['message'])) ? $result['message'] : "Success";
        }
        else{
            $data['status']     = false;
            $data['data']       = (!empty($result['data'])) ? $result['data'] : '';
            $data['message']    = (!empty($result['message'])) ? $result['message'] : "Error";
        }

        $data['statusCode'] = (isset($result['statusCode'])) ? $result['statusCode'] : -1;

        return $data;
    }
}