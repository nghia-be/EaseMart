<?php

namespace App\Policies;

use App\Models\Modules;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class RolePermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    private $listControllerDontNeedPolicy =[null, 'home', 'profile','file']; // danh sách uri-controller không cần check acl
    public function __construct()
    {
        //
    }
    public function rolePermissionPolicy(){
        $moduleUri = request()->segment(1);
        $action = request()->segment(2);
        $flag_action = true;
        $user_role = Auth::user()->role_id;
        if($user_role == ADMIN || in_array($moduleUri,$this->listControllerDontNeedPolicy)){
            return true;
        }
        if(empty($user_role)){
            return false;
        }
        if(!empty($moduleUri) && !in_array($moduleUri,$this->listControllerDontNeedPolicy) && $user_role != ADMIN){
            $listModuleByUser =(new Modules())->getModulesGroupByParent(Auth::user()->role_id)->listModule;
            if(empty($listModuleByUser)){
                return false;
            }
            $listUriModule = array_map(function ($module){
                return $module->uri;
            }, $listModuleByUser);
            if(!in_array($moduleUri,$listUriModule)){
                return false;
            };
            if(empty($action)){
                return true;
            }
            $key =  array_search($moduleUri, array_column(json_decode(json_encode($listModuleByUser), TRUE), 'uri'));
            if (!isset($listModuleByUser[$key])) {
                return false;
            }
            switch($action){
                case "update":
                case "edit":
                    $flag_action = !$listModuleByUser[$key]->update ? false:true;
                    break;
                case "create":
                case "store":
                    $flag_action = !$listModuleByUser[$key]->create ? false:true;
                    break;
                case "delete":
                case "destroy":
                    $flag_action = !$listModuleByUser[$key]->delete ? false:true;
                    break;
                case "save":
                    $request = request()->all();
                    if(empty($request['id'])){
                        $flag_action = !$listModuleByUser[$key]->create ? false:true;
                    }else{
                        $flag_action = !$listModuleByUser[$key]->update ? false:true;
                    }     
            }
            return $flag_action;
        }
        return true;
    }
}
