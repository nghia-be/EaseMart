<?php

namespace App\Repository\Hi_FPT;

use App\Contract\Hi_FPT\BannerManageInterface;
use App\Http\Traits\DataTrait;
use App\Services\ExportClickService;
use App\Services\NewsEventService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class BannerManageRepository implements BannerManageInterface
{
    use DataTrait;
    use ExportClickService;

    private $listMethod;
    private $client;
    private $headers;
    private $listTypeBanner;
    private $listTargetRoute;

    /**
     * @throws GuzzleException
     */
    public function __construct()
    {
        $api_config = '';
        $this->listMethod = '';
        if ($api_config) {
            $this->client = new Client(['base_uri' => $api_config['URL']]);
            $this->headers = [
                'Authorization' => md5($api_config['CLIENT_KEY'] . "::" . $api_config['SECRET_KEY'] . date("Y-d-m")),
                'clientKey' => $api_config['CLIENT_KEY'],
                'Content-Type' => 'application/json'
            ];
            $this->listTypeBanner = json_decode($this->client->request('GET', $this->listMethod['GET_LIST_TYPE_BANNER'], [
                'headers' => $this->headers
            ])->getBody()->getContents())->data;
            $this->listTargetRoute = json_decode($this->client->request('GET', $this->listMethod['GET_LIST_TARGET_ROUTE'], [
                'headers' => $this->headers
            ])->getBody()->getContents())->data;
        }
    }

    /**
     * @throws GuzzleException
     */
    public function all($dataTable, $params)
    {
        return $dataTable->with([])->render('banners.index');
    }

    public function show($id)
    {
        try {
            $response = json_decode($this->client->request('GET', $this->listMethod['GET_DETAIL_BANNER'], [
                'headers' => $this->headers,
                'query' => ['bannerId' => $id]
            ])->getBody()->getContents())->data;
            $data = [
                'list_target_route' => $this->listTargetRoute,
                'list_type_banner' => $this->listTypeBanner
            ];
            if (empty($response)) {
                return response()->json(['status_code' => '500', 'message' => 'System maintain!']);
            }

            $data['banner'] = collect($response)->only([
                "event_id", "event_type", "public_date_start", "public_date_end", "title_vi",
                "title_en", "direction_id", "event_url", "image", "thumb_image", "view_count",
                "date_created", "created_by", "cms_note", "is_show_home"]);
            return $data;
        } catch (GuzzleException $e) {
            return $e->getMessage();
        }
    }

    public function store($params): \Illuminate\Http\JsonResponse
    {
        try {

            $form_params = collect($params->validated())->merge([
                'thumbImageFileName'    => (!empty($params->thumbImageFileName)) ? $params->thumbImageFileName : $params->imageFileName,
                'publicDateStart' => Carbon::parse($params->input('show_from'))->format('Y-m-d H:i:s'),
                'publicDateEnd' => Carbon::parse($params->input('show_to'))->format('Y-m-d H:i:s'),
                'directionId' => $params->input('has_target_route') == 'checked' ? $params->input('direction_id', '') : '',
                'directionUrl' => $params->input('has_target_route') == 'checked' && $params->input('direction_id') == 1 ? $params->input('directionUrl', '') : '',
                'isShowHome' => $params->has('isShowHome') ? 1 : null,
                'cms_note' => json_encode([
                    'created_by' => substr(auth()->user()->email, 0, strpos(auth()->user()->email, '@')),
                    'modified_by' => null
                ])
            ])->toArray();
            // dd($form_params);
            $form_params = $this->getArr($form_params);
            $response = $this->client->request('POST', $this->listMethod['CREATE_BANNER'], [
                'headers' => $this->headers,
                // 'form_params' => array_filter($form_params)
                'body'  => json_encode($form_params)
            ])->getBody()->getContents();
            // dd($this->listMethod['CREATE_BANNER']);
            return response()->json(['data' => json_decode($response)]);
        } catch (GuzzleException $e) {
            return response()->json(['status_code' => '500', 'message' => $e->getMessage()]);
        }
    }

    public function update($params, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $form_params = collect($params->validated())->merge([
                'bannerId' => $id,
                'isShowHome' => $params->has('isShowHome') ? 1 : null,
                'imageFileName' => $params->input('imageFileName'),
                'thumbImageFileName' => $params->input('thumbImageFileName'),
                'publicDateStart' => Carbon::parse($params->input('show_from'))->format('Y-m-d H:i:s'),
                'publicDateEnd' => Carbon::parse($params->input('show_to'))->format('Y-m-d H:i:s'),
                'titleVi' => $params->input('titleVi', ''),
                'titleEn' => $params->input('titleEn', ''),
                'directionId' => $params->input('has_target_route') == 'checked' ? $params->input('direction_id', '') : '',
                'directionUrl' => $params->input('has_target_route') == 'checked' && $params->input('direction_id') == 1 ? $params->input('directionUrl', '') : '',
            ])->toArray();
            $form_params = $this->getArr($form_params);
            $response = $this->client->request('POST', $this->listMethod['UPDATE_BANNER'], [
                'headers' => $this->headers,
                'json' => array_filter($form_params)
            ])->getBody()->getContents();
            return response()->json(['data' => json_decode($response)]);
        } catch (GuzzleException $e) {
            return response()->json(['status_code' => '500', 'message' => $e->getMessage()]);
        }
    }

    public function update_order($params): \Illuminate\Http\JsonResponse
    {
        try {
            $form_params = [
                'orderings' => [
                    ['eventId' => $params->eventId, 'ordering' => $params->ordering]
                ]
            ];
            $response = $this->client->request('POST', $this->listMethod['UPDATE_ORDERING'], [
                'headers' => $this->headers,
                'body' => array_filter($form_params)
            ])->getBody()->getContents();
            return response()->json(json_decode($response));
        } catch (GuzzleException $e) {
            return response()->json(['status_code' => '500', 'message' => $e->getMessage()]);
        }
    }

    public function export_click_phone($params, $id)
    {
        return $this->export_click($this->listMethod['GET_LIST_CLICK_BANNER'],$params, $id);
    }

    public function update_banner_fconnect($params): \Illuminate\Http\JsonResponse
    {
        try {
            $api_config = config('configDomain.DOMAIN_API.' . env('APP_ENV'));
            $headers = [
                'Authorization' => $api_config['CLIENT_KEY'] . "::" . md5($api_config['CLIENT_KEY'] . "::" . $api_config['SECRET_KEY'] . date("Y-d-m")),
                'clientKey' => $api_config['CLIENT_KEY']
            ];
            $url = 'https://hi-static.fpt.vn/upload/images/event/';
            if(empty($params->imageFileName)) {
                return response()->json(['statusCode' => '500', 'message' => 'Đã xảy ra lỗi !!']);
            }
            $form_params = [
                'bannerImage' => $url . $params->imageFileName
            ];
            $client = new Client(['base_uri' => config('configDomain.DOMAIN_API.' . env('APP_ENV'))['URL']]);
            $response = $client->request('POST', $this->listMethod['FCONNECT_UPDATE_BANNER'], [
                'headers' => $headers,
                'json' => array_filter($form_params)
            ])->getBody()->getContents();
            return response()->json(json_decode($response));
        } catch (GuzzleException $e) {
            return response()->json(['status_code' => '500', 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param array $form_params
     * @return array
     */
    public function getArr(array $form_params): array
    {
        foreach ($form_params as $key => $item) {
            if ($key == 'bannerType') {
                if ($item == 'highlight') {
                    $form_params[$key] = 'bannerHome';
                } else if ($item == 'bill') {
                    $form_params[$key] = 'billListScreen';
                } else if ($item == 'contract') {
                    $form_params[$key] = 'tvInternet';
                }
            }
            if ($key == 'imageFileName' || $key == 'thumbImageFileName') {
                $form_params[$key] = strstr($form_params[$key], 'event_');
            }
        }
        return $form_params;
    }
}
