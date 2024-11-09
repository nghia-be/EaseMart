<?php

namespace App\Services;

use Illuminate\Http\Request;

class NewsEventService
{
    private $clientKey;
    private $secretKey;
    private $token;
    private $baseUrl;
    private $listMethod;
    public function __construct()
    {
        $this->listMethod = config('configMethod.DOMAIN_NEWS_EVENT');
        $api_config         = config('configDomain.DOMAIN_NEWS_EVENT.' . env('APP_ENV'));
        if ($api_config) {
            $this->baseUrl      = $api_config['URL'];
            $this->clientKey    = $api_config['CLIENT_KEY'];
            $this->secretKey    = $api_config['SECRET_KEY'];
            $this->token        = md5($this->clientKey . "::" . $this->secretKey . date("Y-d-m"));
        }

    }

    public function getListTargetRoute()
    {
        $url = $this->baseUrl . $this->listMethod['GET_LIST_TARGET_ROUTE'];
        $response =  sendRequest($url, null, $this->token, $header = ['clientKey' => $this->clientKey], 'GET');
        return $response;
    }

    public function getListTypeBanner()
    {
        $url = $this->baseUrl . $this->listMethod['GET_LIST_TYPE_BANNER'];
        $response =  sendRequest($url, null, $this->token, $header = ['clientKey' => $this->clientKey], 'GET');
        return $response;
    }

    public function uploadImage($imageFileName, $encodedImage)
    {
        $url = $this->baseUrl . $this->listMethod['UPLOAD_IMAGE'];
        $param = [
            'imageFileName' => $imageFileName,
            'encodedImage'  => $encodedImage
        ];
        $response =  sendRequest($url, $param, $this->token, $header = ['clientKey' => $this->clientKey]);
        return $response;
    }

    public function getListbanner($param = null){
        $url = $this->baseUrl . $this->listMethod['GET_LIST_BANNER'];
        // my_debug($param,false);
        $response =  sendRequest($url, $param, $this->token, $header = ['clientKey' => $this->clientKey],'GET');
        // my_debug($response);
        return $response;
    }

    public function getDetailBanner($bannerId){
        $url = $this->baseUrl . $this->listMethod['GET_DETAIL_BANNER'];
        $param = [
            'bannerId' => $bannerId,
            // 'bannerType'  => $bannerType
        ];
        $response =  sendRequest($url, $param, $this->token, $header = ['clientKey' => $this->clientKey],'GET');
        return $response;
    }

    public function updateOrderBannder($eventId, $ordering){
        $url = $this->baseUrl . $this->listMethod['UPDATE_ORDERING'];

        $updateParam = [
            'orderings'     => [
                [
                    'eventId'  => $eventId,
                    'ordering'  => $ordering
                ]
            ]
        ];
        $response =  sendRequest($url, $updateParam, $this->token, $header = ['clientKey' => $this->clientKey]);
        return $response;
    }

    public function addNewBanner($params){
        $url = $this->baseUrl . $this->listMethod['CREATE_BANNER'];
        $response =  sendRequest($url, $params, $this->token, $header = ['clientKey' => $this->clientKey]);
        return $response;
    }
    public function updateBanner($params){
        $url = $this->baseUrl . $this->listMethod['UPDATE_BANNER'];
        $response =  sendRequest($url, $params, $this->token, $header = ['clientKey' => $this->clientKey]);
        return $response;
    }
    public function addNewPopup($params) {
        $url = $this->baseUrl . $this->listMethod['CREATE_TEMPLATE_POPUP'];
        $response =  sendRequest($url, $params, $this->token, $header = ['clientKey' => $this->clientKey]);
        return $response;
    }
    public function updatePopup($params) {
        $url = $this->baseUrl . $this->listMethod['UPDATE_TEMPLATE_POPUP'];
        $response =  sendRequest($url, $params, $this->token, $header = ['clientKey' => $this->clientKey]);
        return $response;
    }
    public function pushTemplate($params) {
        $url = $this->baseUrl . $this->listMethod['PUSH_POPUP'];
        $response =  sendRequest($url, $params, $this->token, $header = ['clientKey' => $this->clientKey]);
        return $response;
    }
    public function getListTemplatePopup($templateType=null, $perPage = null, $currentPage=null, $orderBy=null, $orderDirection='DESC')
    {
        $url = $this->baseUrl . $this->listMethod['GET_LIST_POPUP'];
        $param = [
            'perPage'           => $perPage,
            'currentPage'       => $currentPage,
            'orderBy'           => $orderBy,
            'orderDirection'    => $orderDirection,
            'templateType'      => $templateType,
        ];
        $response =  sendRequest($url, $param, $this->token, $header = ['clientKey' => $this->clientKey], 'POST');
        return $response;
    }
    public function getDetailPopup($popupId){
        $url = $this->baseUrl . $this->listMethod['GET_DETAIL_POPUP'];
        $param = [
            'templatePersonalId' => $popupId
        ];
        $response =  sendRequest($url, $param, $this->token, $header = ['clientKey' => $this->clientKey],'POST');
        return $response;
    }
    public function getDetailPersonalMap($personMapId){
        $url = $this->baseUrl . $this->listMethod['GET_DETAIL_PERSONAL_MAP'];
        $param = [
            'templatePersonalMapId' => $personMapId
        ];
        $response =  sendRequest($url, $param, $this->token, $header = ['clientKey' => $this->clientKey],'POST');
        return $response;
    }
}
