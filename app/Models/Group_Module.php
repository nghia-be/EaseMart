<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group_Module extends MY_Model
{
    use SoftDeletes;
    protected $table = 'group_module';
    protected $primaryKey = 'id';
    protected $fillable = ['group_module_name','deleted_at','updated_by','created_by'];

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }
    public function updatedBy(){
        return $this->hasOne(User::class,'id','updated_by');
    }
}

