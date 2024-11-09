<?php

namespace App\Services;

class SmsWorldService
{
    private $token;
    private $baseUrl;
    private $prefix;
    private $listMethod;
    public function __construct()
    {
        $api_config         = config('configDomain.DOMAIN_SMS_WORLD.' . env('APP_ENV'));
        $this->baseUrl      = $api_config['URL'];
        $this->prefix      = $api_config['PREFIX'];
        $this->listMethod = config('configMethod.DOMAIN_SMS_WORLD');
    }

    public function login($username,$password){
        $url = $this->baseUrl . $this->prefix . '/' . $this->listMethod['LOGIN'];
        $param = [
            'UserName' => $username,
            'Password' => $password
        ];
        return sendRequest($url,$param,null,$headerArray = ['Abp.TenantId' => '1']);
    }
    public function getlogs($phone,$month,$year,$accessToken){
        $url = $this->baseUrl . $this->prefix . '/' . $this->listMethod['CHECK_LOG'];
        $param = [
            'PhoneNumber' => $phone,
            'Month' => $month,
            'Year' => $year,
        ];
        $headerArray = [
            'tokenSMS' => $accessToken
        ];
        return sendRequest($url,$param,$token = null, $headerArray);
    }
}