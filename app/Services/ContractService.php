<?php

namespace App\Services;
use Illuminate\Http\Request;

class ContractService
{
    private $clientKey;
    private $secretKey;
    private $token;
    private $baseUrl;
    private $version;
    private $listMethod;
    public function __construct(){
        $api_config         = config('configDomain.DOMAIN_INSIDE.' . env('APP_ENV'));
        $this->baseUrl      = $api_config['URL'];
        $this->version      = $api_config['VERSION'];
        $this->clientKey    = $api_config['CLIENT_KEY'];
        $this->secretKey    = $api_config['SECRET_KEY'];
        $this->token        = "$this->clientKey::".md5($this->clientKey . "::" . $this->secretKey . date("Y-d-m"));
        $this->listMethod = config('configMethod.DOMAIN_INSIDE');
    }

    public function getContractInfo($contractNo){
        $url = $this->baseUrl . $this->version .'/'. $this->listMethod['GET_CONTRACT_BY_CONTRACT_NO'];
        $response =  sendRequest($url, array('contractNo'=> $contractNo),$this->token);
        return $response;
    }

    public function getListcontractByPhone($phoneNumber){
        $url = $this->baseUrl . $this->version .'/'. $this->listMethod['GET_CONTRACT_BY_PHONE_NUMBER'];
        $response =  sendRequest($url, array('contractPhone'=> $phoneNumber),$this->token);
        return $response;
    }
}