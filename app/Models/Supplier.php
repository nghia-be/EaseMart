<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends MY_Model
{
    use SoftDeletes;
    protected $table = 'supplier';
    protected $primaryKey = 'id';
    protected $fillable = ['id','supplier_code','name','email','address','phone','note'];
    
}

