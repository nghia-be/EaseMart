<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FtelPhone extends MY_Model
{
    use HasFactory;
    protected $table = 'ftel_phone';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }
    public function updatedBy(){
        return $this->hasOne(User::class,'id','updated_by');
    }
}
