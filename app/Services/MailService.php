<?php

namespace App\Services;

class MailService {
    private $subDomain;
    private $token;
    private $baseUrl;
    public function __construct()
    {
        $api_config         = config('configDomain.DOMAIN_MAIL.' . env('APP_ENV'));
        $this->baseUrl      = $api_config['URL'];
        $this->subDomain    = (!empty($api_config['SUB_DOMAIN'])) ? implode('/', $api_config['SUB_DOMAIN']) . '/' : '';
    }

    public function sendMail($input){
        $url                = $this->baseUrl . $this->subDomain . 'InsertInfoSendMailSMTP';
        // dd($input);
        $response           = sendRequest($url, $input);
        return $response;
    }
}