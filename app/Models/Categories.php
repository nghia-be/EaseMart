<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use stdClass;

class Categories extends MY_Model
{
    use SoftDeletes;
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = ['categories_name','categories_parent_id','image', 'icon','slug', 'deleted_at', 'updated_at', 'created_at'];

//     public function parentCategory()
// {
//     return $this->belongsTo(Categories::class, 'categories_parent_id');
// }
// public function grandParentCategory()
//     {
//         return $this->parentCategory()->belongsTo(Categories::class, 'categories_parent_id');
//     }


    public function getModulesGroupByParent($role_id)
    {
        $result = new stdClass();
        if($role_id != ADMIN){
            $listModule = DB::table('categories')
            ->join('acl_roles','acl_roles.module_id','categories.id')      
            ->whereNull('categories.deleted_at')
            ->whereNull('acl_roles.deleted_at')
            ->get()
            ->toArray();
        }else{
            $listModule = DB::table('categories')
            // ->leftJoin('acl_roles','acl_roles.module_id','modules.id')
            ->whereNull('categories.deleted_at')
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
    // Quan hệ cho danh mục cha
    public function parent()
{
    return $this->belongsTo(CategoriesParent::class, 'categories_parent_id');
}
    // Quan hệ cho danh mục con
   
    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }
    public function updatedBy(){
        return $this->hasOne(User::class,'id','updated_by');
    }
}
