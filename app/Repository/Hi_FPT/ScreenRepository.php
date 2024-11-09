<?php

namespace App\Repository\Hi_FPT;

use App\Contract\Hi_FPT\ScreenInterface;
use App\Http\Controllers\MY_Controller;
use App\Http\Traits\DataTrait;
use App\Models\Employees;
use App\Models\Screen;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use http\Env\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ScreenRepository implements ScreenInterface
{
    use DataTrait;

    public function index($dataTable, $params)
    {
        return $dataTable->render('screen.index');
    }

    public function show($id)
    {
        $data = DB::table('screen')->find($id);
        return view('screen.edit', ['data'=>$data]);
    }

    public function create()
    {
        return view('screen.create');
    }

    public function store($params)
    {
        try {
            $params->has('status') == 'on' ? $params->merge(['status'=> 1]) : $params->merge(['status'=> 0]);
            $params->merge(['created_by' => auth()->user()->id]);
            DB::table('screen')->insert($params->only(['screenId','screenName','typeLog','api_url','image','example_code','status','created_by']));
            switch ($params->input('action')) {
                case 'back':
                    return redirect()->intended('screen');
                case 'stay':
                    return redirect()->back()->with(['success'=>'Update thành công', 'html'=>'Update thành công']);
            }
        } catch (\Exception $e) {
            return back()->with(['error'=>'Lỗi hệ thống', 'html'=>$e->getMessage()]);
        }
    }

    public function update($params, $id)
    {
        $params->has('status') == 'on' ? $params->merge(['status'=> 1]) : $params->merge(['status'=> 0]);
        Screen::find($id)->update($params->only(['screenId','screenName','typeLog','api_url','image','example_code','status']));
        switch ($params->input('action')) {
            case 'back':
                return redirect()->intended('screen')->with(['success'=>'Update thành công', 'html'=>'Update thành công']);
            case 'stay':
                return redirect()->back()->with(['success'=>'Update thành công', 'html'=>'Update thành công']);
        }
    }

    public function delete($request)
    {
        Screen::destroy($request['id']);
        return response()->json(['success'=>'Delete thành công', 'message'=>'Delete thành công']);
    }

}
