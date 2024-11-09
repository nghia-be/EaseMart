<?php

namespace App\Services;

class UploadImageService {
    private $subDomain;
    private $token;
    private $baseUrl;
    public function __construct()
    {
        $api_config         = config('configDomain.DOMAIN_UPLOAD.' . env('APP_ENV'));
        $this->baseUrl      = $api_config['URL'];
        $this->subDomain    = (!empty($api_config['SUB_DOMAIN'])) ? implode('/', $api_config['SUB_DOMAIN']) . '/' : '';
        $this->token        = md5($api_config['CLIENT_KEY'] . '::' . $api_config['SECRET_KEY'] . date('Y-d-m'));
    }

    public function uploadImage($params) {
        $url                = $this->baseUrl . $this->subDomain . 'upload_CMS.php';
        $response           = sendRequest($url, $params, $this->token, [], 'POST');
        return $response;
    }
}