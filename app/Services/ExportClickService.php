<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

trait ExportClickService
{
    public function export_click($method, $params, $id)
    {
        try {
            $date = split_date($params->daterange);
            if (!empty($date)) {
                $from = $date[0];
                $to = $date[1];
            }
            $form_params = [
                $method == 'provider/tool/banner/get-list-click-banner' ? 'eventId' : 'templateId' => $id,
                'dateStart' => $from ?? null,
                'dateEnd' => $to ?? null,
            ];
            $api_config = config('configDomain.DOMAIN_NEWS_EVENT.' . env('APP_ENV'));
            $client = new Client(['base_uri' => $api_config['URL']]);
            $headers = [
                'Authorization' => md5($api_config['CLIENT_KEY'] . "::" . $api_config['SECRET_KEY'] . date("Y-d-m")),
                'clientKey' => $api_config['CLIENT_KEY'],
                'Content-Type' => 'application/json'
            ];
            $response = $client->request('POST', $method, [
                'headers' => $headers,
                'json' => $form_params
            ])->getBody()->getContents();
            $data = json_decode(json_decode($response)->data);
            if (!empty($data)) {
                $phone = [];
                foreach ($data as $value) {
                    $phone[] = ['SDT Khách hàng' => $value];
                }
                return fastexcel($phone)->download('Export_user_'.date('Y-m-d').'.xlsx');
            }
            return redirect()->back()->with(['error' => 'error', 'html'=>'Không có dữ liệu']);

        } catch (GuzzleException $e) {
            return response()->json(['status_code' => '500', 'message' => $e->getMessage()]);
        }
    }
}
