<?php

namespace App\Repository\Hi_FPT;

use App\Contract\Hi_FPT\ResetPasswordWrongInterface;
use App\Http\Traits\DataTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ResetPasswordWrongRepository implements ResetPasswordWrongInterface
{
    use DataTrait;
    private $listMethod;
    private $client;
    private $headers;
    public function __construct()
    {
        $api_config         = config('configDomain.DOMAIN_AUTH.' . env('APP_ENV'));
        $this->listMethod   = config('configMethod.DOMAIN_RESET_PASSWORD_WRONG');
        $this->client       = new Client(['base_uri' => $api_config['URL']]);
        $this->headers      = [
            'Token' => $api_config['CLIENT_KEY'] . "::" . md5($api_config['CLIENT_KEY'] . "::" . $api_config['SECRET_KEY'] . date("Y-d-m"))
        ];
    }

    public function index()
    {
        return view('reset-password-wrong.index');
    }

    public function store($params)
    {
        try {
            $form_params = [
                'phone' => $params['numberPhone']
            ];
            $response = $this->client->request('POST', $this->listMethod['ADD'], [
                'headers' => $this->headers,
                'form_params' => array_filter($form_params)
            ]);
            $data = json_decode($response->getBody()->getContents());
            return back()->with(['success' => $data, 'html'=>'Thông báo! Reset password wrong thành công.']);
        } catch (GuzzleException $e) {
            return response()->json(['data'=>['status_code' => '500', 'message' => $e->getMessage()]]);
        }
    }
}
