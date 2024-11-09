<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $table = 'shipping';
    protected $primaryKey = 'id';
    protected $fillable = [
        'order_id',
        'shipping_status_id',
        'shipping_method',
        'tracking_number'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function shippingStatus()
    {
        return $this->belongsTo(Status::class, 'shipping_status_id');
    }
}
