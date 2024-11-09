<?php

namespace App\Models;


class Stock extends MY_Model
{
    
    protected $table = 'stock';
    protected $primaryKey = 'id';
    protected $fillable = ['id','product_id','unit_code','warehouse_code','quantity','created_by'];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id'); // Liên kết đến sản phẩm
    }
    public function unit()
    {
        return $this->belongsTo(Units::class, 'unit_code', 'unit_code');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_code', 'warehouse_code');
    }

}

