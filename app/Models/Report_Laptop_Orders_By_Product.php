<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report_Laptop_Orders_By_Product extends Model
{
    public $timestamps = true;
    protected $table = 'report_laptop_orders_by_product';
    protected $primaryKey = 'id';
    protected $fillable = ['id',
        'product_id',
        'count_delivered',
        'amount_delivered',
        'order_id',
        'page_view',
        'page_view_user',
        'emp_count',
        'app_users_count',
        'created_at'
    ];
}
