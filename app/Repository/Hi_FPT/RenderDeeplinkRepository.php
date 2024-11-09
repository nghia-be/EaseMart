<?php

namespace App\Repository\Hi_FPT;

use App\Http\Traits\DataTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Contract\Hi_FPT\RenderDeeplinkInterface;

class RenderDeeplinkRepository implements RenderDeeplinkInterface
{
    use DataTrait;

    public function index()
    {
        return view('render-deeplink.index');
    }

    public function store($params)
    {
        $data = 'https://hi.fpt.vn/deeplink?data=';
        $data_encode = '{
  "id": '.$params->id.',
  "title": "'.$params->title.'",
  "actionType": "open_url_in_app_with_access_token",
  "dataAction": "'.$params->dataAction.'",
  "iconUrl": "'.$params->iconUrl.'",
  "isNew": 0,
  "data": null
}
';
        $data .= base64_encode($data_encode);
        return redirect()->back()->with(['success' => 'success', 'html'=> 'Thành công', 'data' => $data]);
        //return view('render-deeplink.index', compact('data'));
    }
}
