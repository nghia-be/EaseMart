<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table = 'status';
    protected $primaryKey = 'id';
    protected $fillable = [
        'status_code', 
        'status_name', 
        'type'
    ];

    // Định nghĩa các trạng thái liên quan đến đơn hàng, giao hàng, thanh toán
    public function orders()
    {
        return $this->hasMany(Order::class, 'order_status_id');
    }

    public function shippings()
    {
        return $this->hasMany(Shipping::class, 'shipping_status_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_status_id');
    }
}
