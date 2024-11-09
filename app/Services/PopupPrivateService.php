<?php

namespace App\Services;

class PopupPrivateService
{
    private string $clientKey;
    private string $secretKey;
    private string $token;
    private string $baseUrl;
    private array $listMethod;
    public function __construct()
    {
        $api_config         = config('configDomain.DOMAIN_CUSTOMER.' . env('APP_ENV'));
        $this->baseUrl      = $api_config['URL'];
        $this->clientKey    = $api_config['CLIENT_KEY'];
        $this->secretKey    = $api_config['SECRET_KEY'];
        $this->token        = md5($this->clientKey.'::'.$this->secretKey.date("Y-d-m"));
        $this->listMethod = config('configMethod.DOMAIN_POPUP_PRIVATE');
    }

    public function get()
    {
        $url = $this->baseUrl . $this->listMethod['GET'];
        return sendRequest($url, null, $this->token);
    }

    public function getPaginate(array $params)
    {
        $url = $this->baseUrl . $this->listMethod['GET_PAGINATE'];
        $param = [
            'size' => $params[0],
            'page' => $params[1]
        ];
        return sendRequest($url, $param, $this->token);
    }

    public function add(array $params)
    {
        $url = $this->baseUrl . $this->listMethod['ADD'];
        $param = [
            'type'          =>$params['0'],
            'actionType'    =>$params['1'],
            'dataAction'    =>$params['2'],
            'iconButtonUrl' =>$params['3'],
            'iconUrl'       =>$params['4'],
            'dateBegin'     =>$params['5'],
            'dateEnd'       =>$params['6'],
            'phoneList'     =>$params['7'] ?? '',
            'titleVi'       =>$params['8'] ?? '',
            'titleEn'       =>$params['9'] ?? '',
            'desVi'         =>$params['10'] ?? '',
            'desEn'         =>$params['11'] ?? '',
//            'popupGroupId'  =>$params['12'] ?? '',
//            'temPerId'      =>$params['13'] ?? ''
        ];
        return sendRequest($url, $param, $this->token);
    }

    public function update(array $params)
    {
        $url = $this->baseUrl . $this->listMethod['UPDATE'];
        $param = [
            'id'            =>$params['0'],
            'type'          =>$params['1'],
            'actionType'    =>$params['2'],
            'dataAction'    =>$params['3'],
            'iconButtonUrl' =>$params['4'],
            'iconUrl'       =>$params['5'],
            'dateBegin'     =>$params['6'],
            'dateEnd'       =>$params['7'],
            'titleVi'       =>$params['8'],
            'titleEn'       =>$params['9'],
            'desVi'         =>$params['10'],
            'desEn'         =>$params['11'],
            'popupGroupId'  =>$params['12'],
            'temPerId'      =>$params['13']
        ];
        return sendRequest($url, $param, $this->token);
    }

    public function getById(array $params)
    {
        $url = $this->baseUrl . $this->listMethod['GET_BY_ID'];
        $param = [
            'id' => $params['0']
        ];
        return sendRequest($url, $param, $this->token);
    }

    public function delete(array $params)
    {
        $url = $this->baseUrl . $this->listMethod['DELETE'];
        $param = [
            'id' => $params['0'],
            'active' => $params['1']
        ];
        return sendRequest($url, $param, $this->token);
    }

    public function import(array $params)
    {
        $url = $this->baseUrl . $this->listMethod['IMPORT'];
        $param = [
            'id'          =>$params['0'],
            'phoneList'   =>$params['1']
        ];
        return sendRequest($url, $param, $this->token);
    }

}
