<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use stdClass;

class Brand extends MY_Model
{
    use SoftDeletes;
    protected $table = 'brand';
    protected $primaryKey = 'id';
    protected $fillable = ['name','image','categories_id','slug','status','deleted_at', 'updated_at', 'created_at'];

//     public function parentCategory()
// {
//     return $this->belongsTo(brand  ::class, 'bran_parent_id');
// }
// public function grandParentCategory()
//     {
//         return $this->parentCategory()->belongsTo(brand::class, 'brand _parent_id');
//     }

public function categories()
{
    return $this->belongsTo(Categories::class, 'categories_id');
}
    public function getModulesGroupByParent($role_id)
    {
        $result = new stdClass();
        if($role_id != ADMIN){
            $listModule = DB::table('brand')
            ->join('acl_roles','acl_roles.module_id','bran.id')      
            ->whereNull('brand.deleted_at')
            ->whereNull('acl_roles.deleted_at')
            ->get()
            ->toArray();
        }else{
            $listModule = DB::table('brand')
            // ->leftJoin('acl_roles','acl_roles.module_id','modules.id')
            ->whereNull('brand.deleted_at')
            // ->whereNull('acl_roles.deleted_at')
            // ->where('acl_roles.role_id',$role_id)
            ->get()
            ->toArray();
        }

        $listGroupModule = DB::table('group_module')->get()->toArray();
        $arrayGroupKey = [];
        foreach($listGroupModule as $groupModule){
            $groupModule->children = [];
            $arrayGroupKey[$groupModule->id] = $groupModule;
        };
        $lastData = [];
        array_walk_recursive($listModule, function($val, $key) use(&$arrayGroupKey,&$lastData) {
            if(array_key_exists($val->group_module_id,$arrayGroupKey)){
                $arrayGroupKey[$val->group_module_id]->children[] = $val;
                $lastData[$val->group_module_id] = $arrayGroupKey[$val->group_module_id];
            }else{
                $lastData[] = $val;
            }
        });
        $result->listModule = $listModule;
        $result->arrayGroupkey = $lastData;
        return $result;
    }
    
    // public function parent(){
    //     return $this->hasOne(Group_Module::class,'id','group_module_id');
    // }
    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }
    // public function updatedBy(){
    //     return $this->hasOne(User::class,'id','updated_by');
    // }
}
