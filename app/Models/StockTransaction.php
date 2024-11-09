<?php

namespace App\Models;


class StockTransaction extends MY_Model
{
    
    protected $table = 'stock_transaction';
    protected $primaryKey = 'id';
    protected $fillable = ['id','transaction_code','transaction_type','note','warehouse_code','supplier_code','created_by'];

    const UPDATED_AT = null;

    public static function createTransaction(array $data)
    {
        $data['created_by'] = auth()->id(); 
        return self::create($data);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_code', 'warehouse_code');
    }
    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }
}

