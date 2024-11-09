<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Otp_Reset_Logs extends MY_Model
{
    protected $table = 'otp_reset_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'phone', 'api_result', 'updated_by','created_by'];

    protected $casts = [
        'created_at'=> 'datetime:H:i:s d-m-Y',
        'updated_at'=> 'datetime:H:i:s d-m-Y'
    ];

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }
    public function updatedBy(){
        return $this->hasOne(User::class,'id','updated_by');
    }
}

