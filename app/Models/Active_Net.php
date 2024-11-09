<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Active_Net extends MY_Model
{
    protected $table = 'active_net';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['count','zone','branch','date_created'];
    
}
