<?php

namespace App\Services;

use App\Models\Icon_approve;
use App\Models\Icon;
use App\Models\Icon_Category;
use App\Models\Icon_Config;
use App\Models\Roles;
use App\Models\User;
use App\Models\Log_activities;

use Illuminate\Support\Facades\Auth;

class IconManagementService {
    private $subDomain;
    private $token;
    private $baseUrl;
    public function __construct()
    {
        $api_config         = config('configDomain.DOMAIN_ICON_MANAGEMENT.' . env('APP_ENV'));
        $this->baseUrl      = $api_config['URL'];
        $this->subDomain    = (!empty($api_config['SUB_DOMAIN'])) ? implode('/', $api_config['SUB_DOMAIN']) . '/' : '';
        $this->token        = md5($api_config['CLIENT_KEY'] . '::' . $api_config['SECRET_KEY'] . date('Y-d-m'));
    }

    public function getAllProduct() {
        // dd($this->token);
        $url                = $this->baseUrl . $this->subDomain . 'products/get-all';
        $response           = sendRequest($url, [], $this->token);
        return $response;
    }

    public function getProductById($id) {
        $url                = $this->baseUrl . $this->subDomain . 'products/get-by-id';
        $response           = sendRequest($url, ['productId' => $id], $this->token);
        return $response;
    }

    public function getProductByListId($list_id) {
        $url                = $this->baseUrl . $this->subDomain . 'products/get-by-id-list';
        $response           = sendRequest($url, ['productIdList' => $list_id], $this->token);
        return $response;
    }

    public function addProduct($params) {
        $url                = $this->baseUrl . $this->subDomain . 'products/add';
        $response           = sendRequest($url, $params, $this->token, [], 'POST');
        return $response;
    }

    public function updateProduct($params) {
        $url                = $this->baseUrl . $this->subDomain . 'products/update';
        $response           = sendRequest($url, $params, $this->token, [], 'POST');
        return $response;
    }

    public function deleteProduct($params) {
        $url                = $this->baseUrl . $this->subDomain . 'products/delete';
        $response           = sendRequest($url, $params, $this->token, [], 'POST');
        return $response;
    }

    public function getAllProductTitle(){
        $url                = $this->baseUrl . $this->subDomain . 'product-titles/get-all';
        $response           = sendRequest($url, [], $this->token);
        return $response;
    }

    public function getProductTitleById($id) {
        $url                = $this->baseUrl . $this->subDomain . 'product-titles/get-by-id';
        $response           = sendRequest($url, ['productTitleId' => $id], $this->token);
        return $response;
    }

    public function getProductTitleByProductId($id) {
        $url                = $this->baseUrl . $this->subDomain . 'product-titles/get-by-product-id';
        $response           = sendRequest($url, ['productId' => $id], $this->token);
        return $response;
    }

    public function addProductTitle($params) {
        $url                = $this->baseUrl . $this->subDomain . 'product-titles/add';
        $response           = sendRequest($url, $params, $this->token, [], 'POST');
        return $response;
    }

    public function updateProductTitle($params) {
        $url                = $this->baseUrl . $this->subDomain . 'product-titles/update';
        $response           = sendRequest($url, $params, $this->token, [], 'POST');
        return $response;
    }

    public function deleteProductTitle($params) {
        $url                = $this->baseUrl . $this->subDomain . 'product-titles/delete';
        $response           = sendRequest($url, $params, $this->token, [], 'POST');
        return $response;
    }

    public function getAllProductConfig() {
        $url                = $this->baseUrl . $this->subDomain . 'product-configs/get-all';
        $response           = sendRequest($url, [], $this->token);
        
        return $response;
    }

    public function getProductConfigById($id) {
        $url                = $this->baseUrl . $this->subDomain . 'product-configs/get-by-id';
        $response           = sendRequest($url, ['productConfigId' => $id], $this->token);
        // dd($response);
        return $response;
    }

    public function getProductConfigByProductId($id) {
        $url                = $this->baseUrl . $this->subDomain . 'product-configs/get-by-product-id';
        $response           = sendRequest($url, ['productId' => $id], $this->token);
        return $response;
    }

    public function addProductConfig($params) {
        $url                = $this->baseUrl . $this->subDomain . 'product-titles/add';
        $response           = sendRequest($url, $params, $this->token, [], 'POST');
        return $response;
    }

    public function updateProductConfig($params) {
        $url                = $this->baseUrl . $this->subDomain . 'product-configs/update';
        $response           = sendRequest($url, $params, $this->token, [], 'POST');
        return $response;
    }

    public function deleteProductConfig($params) {
        $url                = $this->baseUrl . $this->subDomain . 'product-configs/delete';
        $response           = sendRequest($url, $params, $this->token, [], 'POST');
        return $response;
    }

    public function saveByApprovedRole($id) {
        $model = new Icon_approve();
        $approved_data = $model::where('id', $id)->with(['user_requested_by'])->get()->toArray();

        $update_data = [
            "id"                => $id,
            "approved_status"   => "dapheduyet"
        ];

        $table = '';
        $api_function = '';                                  
        switch($approved_data[0]['product_type']) {
            case 'icon_management':
                $table = 'App\Models\Icon';
                break;
            case 'icon_category':
                $table = 'App\Models\Icon_Category';
                break;
            case 'icon_config':
                $table = 'App\Models\Icon_Config';
                break;
        }
        $result = $this->pushApiApproved($approved_data[0]);
        if(!empty($result->message)) {
            $result->message = convert_vi_to_en($result->message);
        }
        $model::where('id', $id)->update([
            'api_logs'  => json_encode($result)
        ]);
        $update_data = array_merge($update_data, ['approved_by' => Auth::check() ? Auth::user()->id : 0, 'approved_at' => date('Y-m-d H:i:s', strtotime('now'))]);
        $table::where('uuid', $approved_data[0]['product_id'])->delete();

        $module = $this->updateById($model, $id, $update_data);
        
        // $this->addToLog($approved_data);
        echo json_encode(['status' => 1, 'message' => 'Success', 'data' => null]);
    }

    public function pushApiApproved($request) {
        $function_name = '';
        switch($request['approved_type']) {
            case 'create':
                $function_name = 'add';
                break;
            case 'update':
                $function_name = 'update';
                break;
            case 'delete':
                $function_name = 'delete';
                break;
        }

        switch($request['product_type']) {
            case 'icon_management':
                $function_name .= 'Product';
                $icon_info = Icon::where('uuid', $request['product_id'])->first();
                $params = [
                    'productNameVi'         => (!empty($icon_info->productNameVi)) ? $icon_info->productNameVi : '',
                    'productNameEn'         => (!empty($icon_info->productNameEn)) ? $icon_info->productNameEn : '',
                    'iconUrl'               => (!empty($icon_info->iconUrl)) ? $icon_info->iconUrl : '',
                    'dataActionStaging'     => (!empty($icon_info->dataActionStaging)) ? $icon_info->dataActionStaging : '',
                    'dataActionProduction'  => (!empty($icon_info->dataActionProduction)) ? $icon_info->dataActionProduction : '',
                    'data'                  => (!empty($icon_info->data)) ? $icon_info->data : '',
                    'actionType'            => (!empty($icon_info->actionType)) ? $icon_info->actionType : '',
                    'content'               => (!empty($icon_info->content)) ? $icon_info->content : '',
                    'isNew'                 => (!empty($icon_info->isNew)) ? $icon_info->isNew : '0',
                    'newBeginDay'           => (!empty($icon_info->newBeginDay)) ? $icon_info->newBeginDay : date('Y-m-d H:i:s', strtotime('today midnight')),
                    'newEndDay'             => (!empty($icon_info->newEndDay)) ? $icon_info->newEndDay : date('Y-m-d H:i:s', strtotime('tomorrow midnight')),
                    'isDisplay'             => (!empty($icon_info->isDisplay)) ? $icon_info->isDisplay : '0',
                    'displayBeginDay'       => (!empty($icon_info->displayBeginDay)) ? $icon_info->displayBeginDay : date('Y-m-d H:i:s', strtotime('today midnight')),
                    'displayEndDay'         => (!empty($icon_info->displayEndDay)) ? $icon_info->displayEndDay : date('Y-m-d H:i:s', strtotime('tomorrow midnight')),
                    'decriptionVi'          => (!empty($icon_info->decriptionVi)) ? $icon_info->decriptionVi : '',
                    'decriptionEn'          => (!empty($icon_info->decriptionEn)) ? $icon_info->decriptionEn : '',
                    'keywords'              => (!empty($icon_info->keywords)) ? $icon_info->keywords : ''
                ];
                if(!empty($icon_info->productId)) {
                    $params['productId'] = intval($icon_info->productId);
                }
                // dd($params);
                break;
            case 'icon_category':
                $function_name .= 'ProductTitle';
                $category = Icon_Category::where('uuid', $request['product_id'])->first();
                
                $params = [
                    'productTitleId'        => (!empty($category->productTitleId)) ? $category->productTitleId : '',
                    'productTitleNameVi'    => (!empty($category->productTitleNameVi)) ? $category->productTitleNameVi : '',
                    'productTitleNameEn'    => (!empty($category->productTitleNameEn)) ? $category->productTitleNameEn : '',
                    'arrayId'               => (!empty($category->arrayId)) ? $category->arrayId : '',
                    'isDisplay'             => (isset($category->isDisplay)) ? $category->isDisplay : '0',
                    'displayBeginDay'       => (isset($category->displayBeginDay)) ? $category->displayBeginDay : date('Y-m-d 00:00:00', strtotime('now')),
                    'displayEndDay'         => (isset($category->displayEndDay)) ? $category->displayEndDay : date('Y-m-d 23:59:59', strtotime('now'))
                ];
                break;
            case 'icon_config':
                $function_name .= 'ProductConfig';
                $config = Icon_Config::where('uuid', $request['product_id'])->first();
                $params = [
                    'productConfigId'       => '',
                    'titleVi'               => (!empty($config->titleVi)) ? $config->titleVi : '',
                    'titleEn'               => (!empty($config->titleEn)) ? $config->titleEn : '',
                    'iconsPerRow'           => (!empty($config->iconsPerRow)) ? intval($config->iconsPerRow) : '',
                    'rowOnPage'             => (!empty($config->rowOnPage)) ? intval($config->rowOnPage) : '',
                    'arrayId'               => (!empty($config->arrayId)) ? $config->arrayId : '',
                    'isDisplay'             => 1,
                    'displayBeginDay'       => !empty($config->displayBeginDay) ? $config->displayBeginDay : date("Y-m-d"),
                    'displayEndDay'         => !empty($config->displayEndDay) ? $config->displayEndDay : date("Y-m-d")
                ];
                if(!empty($config->productConfigId)) {
                    $params['productConfigId'] = intval($config->productConfigId);
                }
                break;
        }
        // dd($params);
        $result = $this->$function_name($params);
        // echo('<pre>');
        // print_r($params);
        // echo('</pre>');
        // dd($result);
        return $result;
    }

    public function updateById($model, $id, $params = array() ){ // ex: (new Roles(), [10,11], ['role_name'=>'4567']);
        if(isset($params['_token'])){
            unset($params['_token']);
        };
        if(isset($params['_method'])){
            unset($params['_method']);
        };
        if(isset($params['password_confirmation']))  {
            unset($params['password_confirmation']);
        };
        $params['updated_by'] = Auth::user()->id;
        return $model::where('id', $id)->update($params);
    }

    public function addToLog(Request $request)
    {
		$tmpStr = '******';
		$listParamNeedProtect = ['password','current_password','password_confirmation'];
		$listParamNeedRemove = ['_token','_pjax','_method'];
		$data = $request->input();
		foreach($listParamNeedRemove as $key){
			if($request->$key){
				unset($data[$key]);
			}
		};
		foreach($listParamNeedProtect as $key){
			if($request->$key){
				$data[$key] = $tmpStr;
			}
		};
    	$log = [];
    	$log['param'] = !empty($data) ? json_encode($data) : '';
    	$log['url'] = request()->url();
    	$log['method'] = request()->method();
    	$log['ip'] = request()->ip();
    	$log['agent'] = request()->header('user-agent');
    	$log['user_id'] = Auth::check() ? Auth::user()->id : 1;
        Log_activities::create($log);
    }
}