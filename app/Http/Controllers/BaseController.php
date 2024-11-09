<?php

namespace App\Http\Controllers;

use App\Models\Group_Module;
use App\Models\Log_activities;
use App\Models\Modules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    protected $user;
    protected $title = 'Title';

    public function __construct()
    {
        $permission = str_replace('-', '', ucwords(request()->segment(1), '-'));
        if (!empty($permission)) {
            $this->middleware('permission:'.$permission.'-view|'.$permission.'-create|'.$permission.'-edit|'.$permission.'-delete|'.$permission.'-import|'.$permission.'-export', ['only' => ['index','store']]);
            $this->middleware('permission:'.$permission.'-create', ['only' => ['create','store']]);
            $this->middleware('permission:'.$permission.'-edit', ['only' => ['edit','update']]);
            $this->middleware('permission:'.$permission.'-delete', ['only' => ['destroy']]);
            $this->middleware('permission:'.$permission.'-import', ['only' => ['import']]);
            $this->middleware('permission:'.$permission.'-export', ['only' => ['export']]);
        }
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->getListModule();
            return $next($request);
        });
    }

    public function getListModule()
    {
        //checking redis
//        $moduleModel = $this->getModel("Modules");
//        $keyName = config('constants.REDIS_KEY.MODULE_BY_'.$permission.'_ID').$this->user->'.$permission.'->id; // redis key: acl '.$permission.' module
//        $data = Redis::get($keyName);
//        if(!is_null($data)) {
//            // dd(now()->format('h:i:s'));
//            $getModuleData = unserialize($data);
//        }else{
//            $getModuleData = $moduleModel->getModulesGroupByParent($this->user->'.$permission.'_id);
//            Redis::set($keyName, serialize($getModuleData));
//        }
//        $data = [];
//        $getModuleData = $moduleModel->getModulesGroupByParent($this->user->'.$permission.'_id);
//        if (!empty($getModuleData->listModule)) {
//            $moduleUri = request()->segment(1);
//            $key =  array_search($moduleUri, array_column(json_decode(json_encode($getModuleData->listModule), TRUE), 'uri'));
//            if (!isset($getModuleData->listModule[$key])) {
//                abort(403);
//            }
//            $data = $getModuleData->listModule[$key];
//        } else {
//            $data = [];
//        }
//        $this->aclCurrentModule = $data;
//        $menu = new \stdClass();

        $user_permission = $this->user->getPermissionsViaRoles()->pluck('name');
        $arr_module = [];
        foreach ($user_permission as $permisstion) {
            $string = explode('-', $permisstion);
            $arr_module[] = strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $string[0]));
        }
        $groupModules = Cache::get('menu-aside');
        // If the menu is not available in cache, generate it and store in cache
        if ($groupModules === null) {
            if ($this->user->hasRole('Super Admin') || $this->user->hasRole('Admin')) {
                $modules = Modules::get();
            } else {
                $modules = Modules::whereIn('uri', array_unique($arr_module))->get();
            }
            $groupModules = [];

            // Loop through group modules to create an array with group module ids as keys

            foreach (Group_Module::all() as $groupModule) {
                $groupModules[$groupModule->id] = (object) [
                    'group_module_name' => $groupModule->group_module_name,
                    'children' => []
                ];
            }
            foreach ($modules as $module) {
                $groupModuleId = $module->group_module_id;
                if (isset($groupModules[$groupModuleId])) {
                    $groupModules[$groupModuleId]->children[] = $module;
                }
            }
            $groupModules = array_filter($groupModules, function ($item) {
                return !empty($item->children);
            });
            // Store the menu in cache for 60 minutes
            Cache::put('menu-aside', $groupModules, 5);
        }
        View::share(['groupModule' => $groupModules, 'title'=>$this->title]);
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
