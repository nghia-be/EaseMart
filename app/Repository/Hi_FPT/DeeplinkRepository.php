<?php

namespace App\Repository\Hi_FPT;

use App\Contract\Hi_FPT\DeeplinkInterface;
use App\Http\Controllers\MY_Controller;
use App\Http\Traits\DataTrait;
use App\Models\Employees;
use App\Models\Deeplink;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DeeplinkRepository implements DeeplinkInterface
{
    use DataTrait;

    public function index($dataTable, $params)
    {
        return $dataTable->render('deeplink.index');
    }

    public function show($id)
    {
        $data = DB::table('deeplinks')->find($id);
        return view('deeplink.edit', ['data'=>$data]);
    }

    public function create()
    {
        return view('deeplink.create');
    }

    public function store($params)
    {
        try {
            DB::table('deeplinks')->insert($params->only(['direction','name','url']));
            switch ($params->input('action')) {
                case 'back':
                    return redirect()->intended('deeplink')->with(['success'=>'Update thành công', 'html'=>'Thêm mới thành công']);
                case 'stay':
                    return redirect()->back()->with(['success'=>'Update thành công', 'html'=>'Thêm mới thành công']);
            }
        } catch (\Exception $e) {
            return back()->with(['error'=>'Lỗi hệ thống', 'html'=>$e->getMessage()]);
        }
    }

    public function update($params, $id)
    {
        Deeplink::find($id)->update($params->only(['direction','name','url']));
        switch ($params->input('action')) {
            case 'back':
                return redirect()->intended('deeplink')->with(['success'=>'Update thành công', 'html'=>'Update thành công']);
            case 'stay':
                return redirect()->back()->with(['success'=>'Update thành công', 'html'=>'Update thành công']);
        }
    }

    public function delete($id)
    {
        Deeplink::destroy($id);
        return response()->json([
            'data' => [
                'statusCode'=> 0,
                'message'=>'Delete thành công id '.$id
            ]
        ]);
    }

}
