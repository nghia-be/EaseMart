<?php

namespace App\Repository\Hi_FPT;

use App\Contract\Hi_FPT\StatisticInterface;
use App\Http\Traits\DataTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class StatisticRepository implements StatisticInterface
{
    use DataTrait;

    private $listMethod;
    private $headers;
    private $listTypeBanner;
    private $listTargetRoute;

    /**
     * @throws GuzzleException
     */
    public function __construct()
    {
        $api_config = config('configDomain.DOMAIN_API.' . env('APP_ENV'));
        $this->listMethod = config('configMethod.DOMAIN_API');
        $this->client = new Client(['base_uri' => $api_config['URL']]);
        $this->headers = [
            'Authorization' => $api_config['CLIENT_KEY'] . "::" . md5($api_config['CLIENT_KEY'] . "::" . $api_config['SECRET_KEY'] . date("Y-d-m")),
            'clientKey' => $api_config['CLIENT_KEY']
        ];
    }

    public function index($dataTable,$dataTableDetail, $request)
    {
        try {
            $perPage = $request->length ?? null;
            $currentPage = $request->start == 0 ? 1 : ($request->start / $perPage) + 1;
            $form_params = [
                'from' => $request->from ?? '2022-10-10 00:00:00',
                'to' => $request->to ?? '2023-10-10 00:00:00',
                'limit' => $perPage,
                'page' => $currentPage,
            ];
            $data = $this->send_api($form_params, $this->listMethod['STATISTICS_OVERVIEW']);
            $data_detail = $this->send_api($form_params, $this->listMethod['STATISTICS_DETAIL']);

            $table_detail = $dataTableDetail->with([ 'data_detail'=> $data_detail]);
            $table_overview = $dataTable->with(['data'=> $data]);

            if ($request->ajax() && request()->get('table') == 'detail') {
                return $table_detail->render('statistics.index');
            }
            if ($request->ajax() && request()->get('table') == 'overview') {
                return $table_overview->render('statistics.index');
            }
            return view('statistics.index', [
                    'overview'          => $table_overview->html(),
                    'detail'            => $table_detail->html(),
                    'overview_title'    => $data->title,
                    'detail_title'      => $data_detail->title
                ]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function detail($dataTable, $request)
    {
        try {
            $form_params = [
                'from' => $request->from ?? '2022-10-10 00:00:00',
                'to' => $request->to ?? '2023-10-10 00:00:00',
                'limit' => 100,
                'page' => '3',
            ];
            $data = $this->send_api($form_params, $this->listMethod['STATISTICS_OVERVIEW']);
            return $dataTable->with([
                'data' => $data
            ])->render('statistics.index');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function send_api($form_params, $method)
    {
        $response = $this->client->request('POST', $method, [
            'headers' => $this->headers,
            'query' => $form_params
        ])->getBody()->getContents();
        return json_decode($response)->data->table;
    }

}
