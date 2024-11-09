<?php

namespace App\Models;


class StockTransactionDetail extends MY_Model
{
    
    protected $table = 'stock_transaction_detail';
    protected $primaryKey = 'id';
    protected $fillable = ['id','quantity','product_id','product_transaction_id','unit_code','created_at'];
    const UPDATED_AT = null;
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); // Quan hệ với bảng products
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_code'); // Quan hệ với bảng units
    }
}

