<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Roles extends MY_Model
{
    use SoftDeletes;
    protected $table = 'roles_old';
    protected $primaryKey = 'id';
    protected $fillable = ['role_name','deleted_at','updated_by','created_by'];

    public function acls(){
        return $this->hasMany(Acl_Roles::class,'role_id','id');
    }
    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }
}
