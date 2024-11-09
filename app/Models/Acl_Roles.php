<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acl_Roles extends MY_Model
{
    use SoftDeletes;
    protected $table = 'acl_roles';
    protected $primaryKey = 'id';
    protected $fillable = ['role_id','module_id','deleted_at','updated_by','created_by'];


    public static function deleteEmptyAclRole($role_id, $except_list_module){
        $query =  Acl_Roles::where('role_id',$role_id);
        if(!empty($except_list_module)){
            $query = $query->whereNotIn('module_id',$except_list_module);
        }
        return $query->delete();
    }
}
