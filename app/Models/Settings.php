<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class Settings extends MY_Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $fillable = ['name','value','deleted_at','updated_by','created_by'];

    public function icon_approve() {
       return $this->hasMany(Icon_approve::class, 'value->key', 'approved_status');
    }
    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }
}
