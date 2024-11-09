<?php

namespace App\Repository\Hi_FPT;

use App\Contract\Hi_FPT\PopupManageInterface;
use App\Http\Traits\DataTrait;
use App\Services\ExportClickService;
use App\Services\NewsEventService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PopupManageRepository implements PopupManageInterface
{
    use DataTrait;
    use ExportClickService;
    private $listMethod;
    private $client;
    private $headers;
    private $api_config;
    public function __construct()
    {
        $this->api_config         = config('configDomain.DOMAIN_NEWS_EVENT.'.env('APP_ENV'));
        $api_config         = config('configDomain.DOMAIN_NEWS_EVENT.'.env('APP_ENV'));
        if ($api_config) {
            $this->listMethod   = config('configMethod.DOMAIN_NEWS_EVENT');
            $this->client       = new Client(['base_uri' => $api_config['URL']]);
            $this->headers      = [
                'Authorization' => md5($api_config['CLIENT_KEY'] . "::" . $api_config['SECRET_KEY'] . date("Y-d-m")),
                'clientKey'     => $api_config['CLIENT_KEY']
            ];
        }

    }

    public function all($dataTable, $params)
    {
        try {
            $perPage = $request->lenght ?? '10';
            $form_params = [
                'perPage'           => $perPage,
                'currentPage'       => $params->start / $perPage + 1 ?? '1',
                'orderBy'           => 'dateCreated',
                'orderDirection'    => 'DESC',
                'templateType'      => $params->templateType ?? '',
            ];
            $response = $this->client->request('POST', $this->listMethod['GET_LIST_POPUP'], [
                'headers' => $this->headers,
                'form_params' => $form_params
            ]);
            $res = json_decode($response->getBody()->getContents());
            $newsEventService = new NewsEventService();
            $list_route = $newsEventService->getListTargetRoute()->data ?? null;
            $list_type_popup = config('platform_config.type_popup_service');
            return $dataTable->with([
                'data'=> $res,
                'list_route' => $list_route
            ])->render('popup.index', compact('list_type_popup', 'list_route'));
        } catch (GuzzleException $e) {
            return $e->getMessage();
        }
    }

    public function show($dataTable, $id)
    {
        try {
            $response = $this->client->request('POST', $this->listMethod['GET_DETAIL_POPUP'], [
                'headers' => $this->headers,
                'form_params' => [
                    'templatePersonalId' => $id
                ]
            ])->getBody()->getContents();
            if (!check_status_code_api(json_decode($response))) {
                return redirect()->back()->withErrors(json_decode($response)->message ?? 'Error API!');
            }
            $object_type    = config('platform_config.object_type');
            $object         = config('platform_config.object');
            $repeatTime     = config('platform_config.repeatTime');

            return $dataTable->with([
                'data' => json_decode($response)
            ])->render('popup.view', compact('object_type', 'repeatTime', 'object', 'id'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function store(array $params)
    {
        try {
            $form_params = [
                'templateType'      => $params['templateType'],
                'titleVi'           => $params['titleVi'],
                'titleEn'           => $params['titleEn'],
                'image'             => $params['image_popup_name'],
                'directionId'       => $params['directionId'] ?? '',
                'buttonImage'       => $params['buttonImage_popup_name'] ?? "",
                'directionUrl'      => $params['directionId'] == '1' ? $params['directionUrl'] : null,
                'templatePersonalId'=> $params['id_popup'] ?? null
            ];

            $method = $params['id_popup'] ? 'UPDATE_TEMPLATE_POPUP' : 'CREATE_TEMPLATE_POPUP';
            $response = $this->client->request('POST', $this->listMethod[$method], [
                'headers' => $this->headers,
                'form_params' => $form_params
            ])->getBody()->getContents();
            return response()->json(['data' => json_decode($response)]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function push(array $params)
    {
        try {
            $timeline_array = explode(" - ", $params['daterange']);
            $form_params = [
                'popupTemplateId' => $params['templateId'],
                'repeatTime' => $params['repeatTime'],
                'dateStart' => $timeline_array[0],
                'dateEnd' => $timeline_array[1],
                'objectType' => $params['objecttype'],
                'objects' => $params['object'],
            ];
            $response = $this->client->request('POST', $this->listMethod['PUSH_POPUP'], [
                'headers' => $this->headers,
                'form_params' => $form_params
            ])->getBody()->getContents();
            return response()->json(['data' => json_decode($response)]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function detail($id)
    {
        try {
            $response = $this->client->request('POST', $this->listMethod['GET_DETAIL_POPUP'], [
                'headers' => $this->headers,
                'form_params' => [
                    'templatePersonalId' => $id
                ]
            ])->getBody()->getContents();
            if (!check_status_code_api(json_decode($response))) {
                return redirect()->back()->withErrors(json_decode($response)->message ?? 'Error API!');
            }
            return response()->json(json_decode($response)->data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function export_click_phone($params, $id)
    {
        return $this->export_click($this->listMethod['GET_LIST_CLICK_POPUP'],$params, $id);
    }

    public function getDetailPersonalMaps($id)
    {
        try {
            $response = $this->client->request('POST', $this->listMethod['GET_DETAIL_PERSONAL_MAP'], [
                'headers' => $this->headers,
                'form_params' => [
                    'templatePersonalMapId' => $id
                ]
            ])->getBody()->getContents();
            if (!check_status_code_api(json_decode($response))) {
                return redirect()->back()->withErrors(json_decode($response)->message ?? 'Error API!');
            }
            return response()->json(json_decode($response), ResponseAlias::HTTP_OK);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
