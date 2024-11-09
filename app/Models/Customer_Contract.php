<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Awobaz\Compoships\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class Customer_Contract extends Model
{
    protected $connection = 'mysql4';
    protected $table = 'customer_contract';
    protected $primaryKey = ['customer_id', 'contract_id'];
    public $timestamps = false;
    protected $fillable = ['customer_id', 'contract_id', 'contract_no', 'customer_phone', 'is_report_support_chat', 'contract_phone', 'customer_set_contract_name', 'is_fsafe', 'last_time_service_feedback', 'customer_is_active', 'is_deleted', 'order', 'flag', 'type', 'date_created', 'date_modified'];

    
}
