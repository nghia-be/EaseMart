<?php

namespace App\Services;

class AirDirectionService
{
    private $clientKey;
    private $secretKey;
    private $token;
    private $baseUrl;
    private $listMethod;
    public function __construct()
    {
        $api_config         = config('configDomain.DOMAIN_CUSTOMER.' . env('APP_ENV'));
        $this->baseUrl      = $api_config['URL'];
        $this->clientKey    = $api_config['CLIENT_KEY_AIR_DIRECTION'];
        $this->secretKey    = $api_config['SECRET_KEY_AIR_DIRECTION'];
        $this->token        = md5($this->clientKey.'::'.$this->secretKey.date("Y-d-m"));
        $this->listMethod   = config('configMethod.DOMAIN_AIR_DIRECTION');
    }

    public function get()
    {
        $url = $this->baseUrl . $this->listMethod['GET'];
        return sendRequest($url, null, $this->token,$headerArray=['clientKey'=>$this->clientKey], 'GET');
    }

    public function add(array $params)
    {
        $url = $this->baseUrl . $this->listMethod['ADD'];
        $param = [
            'name'              =>$params['0'],
            'decription'        =>$params['1'],
            'value'             =>$params['2'],
            'key'             =>$params['3'],
        ];
        return sendRequest($url, $param, $this->token,$headerArray=['clientKey'=>$this->clientKey]);
    }

    public function update(array $params)
    {
        $url = $this->baseUrl . $this->listMethod['EDIT'];
        $param = [
            'id'                =>$params['0'],
            'name'              =>$params['1'],
            'decription'       =>$params['2'],
            'value'             =>$params['3'],
            'key'             =>$params['4'],
        ];
        return sendRequest($url, $param, $this->token,$headerArray=['clientKey'=>$this->clientKey]);
    }

    public function getById(array $params)
    {
        $url = $this->baseUrl . $this->listMethod['ITEM'];
        $param = [
            'id' => $params['0']
        ];
        return sendRequest($url, $param, $this->token,$headerArray=['clientKey'=>$this->clientKey]);
    }

    public function delete(array $params)
    {
        $url = $this->baseUrl . $this->listMethod['DELETE'];
        $param = [
            'id' => $params['0']
        ];
        return sendRequest($url, $param, $this->token,$headerArray=['clientKey'=>$this->clientKey]);
    }

}
