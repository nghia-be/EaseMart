<?php

namespace App\Services;

class HrService
{
    private $token;
    private $baseUrl;
    private $username;
    private $password;
    private $listMethod;
    public function __construct()
    {
        $api_config         = config('configDomain.DOMAIN_HR.' . env('APP_ENV'));
        $this->baseUrl      = $api_config['URL'];
        $this->username      = $api_config['USERNAME'];
        $this->password     = $api_config['PASSWORD'];
        $this->listMethod = config('configMethod.DOMAIN_HR'); 
    }

    public function loginHr(){
        $url = $this->baseUrl . $this->listMethod['LOGIN'];
        
        $param = [
            'username' => $this->username,
            'password' => $this->password
        ];
        return sendRequest($url,$param,null,$headerArray = ['Abp.TenantId' => '1']);
    }

    public function getInfoEmployee($phone, $token) {
        $url = $this->baseUrl . $this->listMethod['GET_EMPLOYEE_INFO'];
        $param = [
            'phonenumber' => $phone
        ];
        if(!empty($token)) {
            return $response = sendRequest($url,$param,'Bearer '.$token,$headerArray = ['Abp.TenantId' => '1']);
        }else{
            return $response = 'Error!';
        }
    }

    public function getListInfoEmployee(array $listPhone, $token) {
        $url = $this->baseUrl . $this->listMethod['GET_LIST_EMPLOYEE_INFO'];
        $param = [
            "phonenumber" => $listPhone
        ];
 
        if(!empty($token)) {
            return $response = sendRequest($url,$param,'Bearer '.$token,$headerArray = ['Abp.TenantId' => '1']);
        }else{
            return $response = 'Error!';
        }
    }
}