<?php

namespace App\Repository\Hi_FPT;

use App\Contract\Hi_FPT\SectionLogInterface;
use App\Http\Traits\DataTrait;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;

class SectionLogRepository implements SectionLogInterface
{
    use DataTrait;

    /**
     * @throws GuzzleException
     */

    public function all($dataTable, $params)
    {
        $perPage = $params->length ?? null;
        $currentPage = $params->start == 0 ? 1 : ($params->start / $perPage) + 1;
        $form_params = [
            'banner_type' => $params->bannerType ?? null,
            'public_date_start' => $params->public_date_start ?? null,
            'public_date_end' => $params->public_date_end ?? null,
            'order_by' => $params->columns[$params->order[0]['column']??0]['data'] ?? 'event_id',
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'order_direction' => $params->order[0]['dir'] ?? 'desc'
        ];
        $response = $this->client->request('GET', $this->listMethod['GET_LIST_BANNER'], [
            'headers' => $this->headers,
            'query' => $form_params
        ])->getBody()->getContents();
        return $dataTable->with([
            'data'=>json_decode($response)
        ])->render('banners.index', ['list_type_banner' => $this->listTypeBanner, 'list_target_route'=>$this->listTargetRoute]);
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
                'list_type_banner'  => $this->listTypeBanner
            ];
            if(empty($response)) {
                return response()->json(['status_code' => '500', 'message' => 'System maintain!']);
            }

            $data['banner'] = collect($response)->only([
                "event_id","event_type","public_date_start","public_date_end","title_vi",
                "title_en","direction_id","event_url","image","thumb_image","view_count",
                "date_created","created_by","cms_note","is_show_home"]);
            return $data;
        } catch (GuzzleException $e) {
            return $e->getMessage();
        }
    }

    public function store($params): \Illuminate\Http\JsonResponse
    {
        try {
            $form_params = collect($params->validated())->merge([
                'publicDateStart'   => Carbon::parse($params->input('show_from'))->format('Y-m-d H:i:s'),
                'publicDateEnd'     => Carbon::parse($params->input('show_to'))->format('Y-m-d H:i:s'),
                'directionId'       => $params->input('has_target_route')=='checked' ? $params->input('direction_id', '') : '',
                'directionUrl'      => $params->input('has_target_route')=='checked' && $params->input('direction_id')==1 ? $params->input('directionUrl', '') : '',
                'isShowHome'        => $params->has('isShowHome') ? 1 : null,
                'cms_note'          => json_encode([
                    'created_by' => substr(auth()->user()->email, 0, strpos(auth()->user()->email, '@')),
                    'modified_by' => null
                ])
            ])->toArray();
            $form_params = $this->getArr($form_params);
            $response = $this->client->request('POST', $this->listMethod['CREATE_BANNER'], [
                'headers' => $this->headers,
                'form_params' => array_filter($form_params)
            ])->getBody()->getContents();
            return response()->json(['data' => json_decode($response)]);
        } catch (GuzzleException $e) {
            return response()->json(['status_code' => '500', 'message' => $e->getMessage()]);
        }
    }
}
