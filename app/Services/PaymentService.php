<?php

namespace App\Services;

use Illuminate\Http\Request;

class PaymentService
{
    private $clientKey;
    private $secretKey;
    private $token;
    private $baseUrl;
    private $listMethod;
    public function __construct()
    {
        $api_config         = config('configDomain.DOMAIN_PAYMENT.' . env('APP_ENV'));
        $this->baseUrl      = $api_config['URL'];
        $this->clientKey    = $api_config['CLIENT_KEY'];
        $this->secretKey    = $api_config['SECRET_KEY'];
        $this->token        = $this->clientKey . "::" .md5($this->clientKey."::".$this->secretKey.date("Y-d-m"));
        $this->listMethod = config('configMethod.DOMAIN_PAYMENT');
    }

    public function get_transaction_by_phone(string $phone, $from, $to)
    {
        $url = $this->baseUrl . $this->listMethod['GET_TRANSACTION_BY_PHONE'];
        $param = [
            'phone'=>$phone,
            'fromDate'=>$from,
            'toDate'=>$to
        ];
        return sendRequest($url, $param, $this->token);
    }

}
