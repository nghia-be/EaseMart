<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProductUnits extends MY_Model
{
    
    protected $table = 'product_units';
    protected $primaryKey = 'id';
    protected $fillable = ['id','unit_code','product_id','price','price_sale','level','exchangrate','status'];
    
    public function product()
        {
            return $this->belongsTo(Products::class, 'product_id', 'id');
        }

    // Mối quan hệ với Unit
    public function unit()
    {
        return $this->belongsTo(Units::class, 'unit_code', 'unit_code');
    }



}

