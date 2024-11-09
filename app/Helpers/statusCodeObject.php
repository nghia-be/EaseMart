<?php
namespace App\Helpers;
class statusCodeObject{

    const HTTP_OK = [
        'code' => 200,
        'message' => 'Thành Công',
        'message_en' => 'Success!',
    ];

    const INTERNAL_SERVER_ERROR = [
        'code' => 500,
        'message' => 'Lỗi hệ thống',
        'message_en' => 'Internal server error',
    ];

    const SERVICE_UNAVAILABLE = [
        'code' => 503,
        'message' => 'Hệ thống đang bảo trì',
        'message_en' => 'Service unavailable',
    ];

    const INVALID_INPUT = [
        'code' => 400,
        'message' => 'Đầu vào không hợp lệ',
        'message_en' => 'Invalid Input',
    ];

    const PAGE_NOT_FOUND = [
        'code' => 404,
        'message' => 'Không tìm thấy',
        'message_en' => 'Page not found',
    ];

    const UNAUTHORIZED = [
        'code' => 401,
        'message' => 'xác thực không thành công',
        'message_en' => 'Unauthorized',
    ];

    const FORBIDDEN = [
        'code'  => 403,
        'message'  => 'Không có quyền truy cập',
        'message_en' => 'Forbiden',
    ];

    const METHOD_NOT_ALLOWED = [
        'code'  => 405,
        'message' => 'phương thức không hỗ trợ',
        'message_en' => 'Method not allowed',
    ];

    const REQUEST_TIMEOUT = [
        'code' => 408,
        'message'   => 'thời gian phản hồi quá lâu',
        'message_en' => 'Response Time out',
    ];

    const GATEWAY_TIMEOUT =[
        'code' => 504,
        'message' => 'Gateway timeout',
        'message_en' => 'Gateway timeout',
    ];

    const DATA_EMPTY =[
        'code' => 204,
        'message' => 'Không có dữ liệu',
        'message_en' => 'Data empty',
    ];

    public static function getObject($status){
       if(defined('self::'.$status)){
            return (object)constant("self::".$status);
       }else{
            return (object)constant("self::INTERNAL_SERVER_ERROR");
       }
    }
}
