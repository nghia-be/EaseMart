<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends MY_Model
{
    use SoftDeletes;
    protected $table = 'warehouse';
    protected $primaryKey = 'id';
    protected $fillable = ['id','warehouse_code','name','email','address','phone','status'];
    
}

