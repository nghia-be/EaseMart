<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Groups extends MY_Model
{
    use SoftDeletes;
    protected $table = 'groups';
    protected $primaryKey = 'id';
    protected $fillable = ['group_name','deleted_at','updated_by','created_by'];

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }
    public function updatedBy(){
        return $this->hasOne(User::class,'id','updated_by');
    }
}
