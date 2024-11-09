<?php

namespace App\Http\Controllers;

use App\Models\Log_activities;
use App\Models\Modules;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\View;

class MY_Controller extends Controller
{
    protected $user;
    protected $module_name;
    protected $model;
    protected $redis;
    protected $aclCurrentModule;
    /**
     * model of module
     * @var $model_name
     */
    protected $model_name;
    /**
     * current controller name
     * @var $controller_name
     */
    protected $controller_name;

    /**
     * current action name
     * @var $action_name
     */

    /**
     * @var string
     */
    protected $action_list = 'view';
    /**
     * @var string
     */
    protected $action_detail = 'detail';
    /**
     * @var string
     */
    protected $action_edit = 'edit';
    /**
     * @var string
     */
    protected $action_delete = 'delete';

    protected $title = 'Title';


    /**
     * @var null
     */
    protected $link_detail = null;
    /**
     * @var null
     */
    protected $link_action = null;

    protected $action_name;
    public function __construct()
    {
        $this->beforeExecuteRoute();
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            if(!empty($this->user->role)){
                $this->getListModule();
            }
            $this->getSetting();
            return $next($request);
        });
    }

    public function getListModule()
    {
        //checking redis
        $moduleModel = $this->getModel("Modules");
        $keyName = config('constants.REDIS_KEY.MODULE_BY_ROLE_ID').$this->user->role->id; // redis key: acl role module
        $data = Redis::get($keyName);
        if(!is_null($data)) {
            // dd(now()->format('h:i:s'));
            $getModuleData = unserialize($data);
        }else{
            $getModuleData = $moduleModel->getModulesGroupByParent($this->user->role_id);
            Redis::set($keyName, serialize($getModuleData));
        }
        $data = [];
        $getModuleData = $moduleModel->getModulesGroupByParent($this->user->role_id);
        if (!empty($getModuleData->listModule)) {
            $moduleUri = request()->segment(1);
            $key =  array_search($moduleUri, array_column(json_decode(json_encode($getModuleData->listModule), TRUE), 'uri'));
            if (!isset($getModuleData->listModule[$key])) {
                abort(403);
            }
            $data = $getModuleData->listModule[$key];
        } else {
            $data = [];
        }
        $this->aclCurrentModule = $data;
        View::share(['groupModule' => $getModuleData->arrayGroupkey, 'aclCurrentModule' => $data,'title'=>$this->title]);
    }
    public function beforeExecuteRoute()
    {
        $this->controller_name = request()->segment(1);
        $this->action_name = request()->segment(2);
        $this->action_name = ($this->action_name == '') ? 'list' : $this->action_name;
    }
    protected function getModel($model_name = null)
    {
        $model_focus = $this->model_name;
        if ($model_name) {
            $model_focus = $model_name;
        }

        if ($model_focus) {
            $model_path = 'App\\Models\\' . ucfirst($model_focus);
            $model = new $model_path();
            /**
             * @var ModelBase $model
             */
            return $model;
        } else {
            return null;
        }
    }
    public function list1()
    {
        $data = null;
        $title = 'List ' . $this->model_name;

        $data['title'] = $title;

        $controller = strtolower($this->controller_name);
        $action = strtolower($this->action_name);

        $data['model_name'] = strtolower($this->model_name);
        $data['module_mane'] = strtolower($this->module_name);

        $data['controller'] = $controller;
        $data['action'] = $action;
        $data['action_list'] = $this->action_list;
        $data['action_detail'] = $this->action_detail;
        $data['action_edit'] = $this->action_edit;
        $data['action_delete'] = $this->action_delete;

        $data['link_action'] = $this->link_action;
        return $data;
    }
    public function edit1()
    {
        $data = func_get_args();
        if (!is_array($data)) {
            $data = array();
        }
        $id = request()->segment(3);
        // get model
        $model = $this->getModel();
        $result = null;

        if ($id) {
            $result = $model::find($id);
            $view_title = !empty($model->edit_view['title']) ? $model->edit_view['title'] : 'name';
            $title = 'Edit ' . $this->model_name . ': ' . $result->$view_title;
        } else {
            $title = 'Create ' . $this->model_name;
        }
        $data['title'] = $title;
        $data['edit_view'] = $model->edit_view;
        $data['model'] = $model;
        if (isset(session()->all()['data'])) {
            $result = (object)session()->all()['data'];
        }
        $data['data'] = $result;

        $controller = strtolower($this->controller_name);
        $action = strtolower($this->action_name);

        $data['model_name'] = $this->model_name;
        $data['controller'] = $controller;
        $data['action'] = $action;
        $data['menu'] = $model->menu;
        $data['action_detail'] = $this->action_detail;
        return $data;
    }

    public function redirect($url = null)
    {
        return redirect('/' . $url);
    }
    private function getSetting(){
        $setting_data = Settings::get();
        View::share(['Settings'=>$setting_data]);
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
