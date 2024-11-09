<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Units extends MY_Model
{
    use SoftDeletes;
    protected $table = 'units';
    protected $primaryKey = 'id';
    protected $fillable = ['id','unit_code','unit_name','status','deleted_at'];
    
    public function stockTransactionDetails()
    {
        return $this->hasMany(StockTransactionDetail::class); // Quan hệ với bảng StockTransactionDetail
    }
}

