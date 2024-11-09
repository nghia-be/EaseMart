<?php

namespace App\Http\Controllers;


use stdClass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\NewsEventService;
use Illuminate\Support\Facades\Storage;


class FileController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImageExternal(Request $request){
        
        if ($request->file('file')) {
            $path = $request->file('file')->store('upload', 'public');
            // Bạn có thể lưu $path vào database nếu cần
            
        }
        $url = Storage::url($path);
        // $request->validate([
        //     'file'  =>'required'
        // ]);
        // $file = $request->file('file');
        // $param = [
        //     'imageFileName'=>  $file->getClientOriginalName(),
        //     'encodedImage' =>   base64_encode(file_get_contents($file))
        // ];
        // $newsEventService = new NewsEventService();
        return response()->json([
            'statusCode'=>0,
            'message'=>'Bạn đã upload thành công',
            'data'=>[
                'uploadedImageFileName'=>$url
            ]

        ]);
    }

    public function importPhone(Request $request)
    {
        $request->validate(['excel.*' => 'mimes:xlsx'],['excel.*.mimes' => 'Sai định dạng file, chỉ chấp nhận file có đuôi .xlsx']);
        $data = [];
        foreach ($request->file('excel') as $key => $item){
            $data['data'][] = fastexcel()->import($item)->flatten();
            if (count($data['data'][$key]) > LIMIT_PHONE) {
                return response()->json(['errors' => ['error'=>'Data in files '.($key+1).' too big <= '.LIMIT_PHONE.'K']], 401);
            }
        }
        return $data;
    }
}
