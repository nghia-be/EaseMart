<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class Insert_Service_Internet extends Model
{
    use HasFactory;
    // public $timestamps = false;
    protected $table = 'insert_service_internet';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'order_id','customer_id','customer_phone','saleteam_id','service_key','sub_type','inside_order_id','inside_order_code','inside_order_status','inside_order_create_time','buyer_full_name','buyer_phone','buyer_email','buyer_birth_date','buyer_passport','buyer_gender','subtotal_cost','discount_amount','total_cost','payment_method','payment_status','order_status','is_debt','payment_order_id', 'payment_merchant_id', 'note', 'deploy_appointment_date', 'deploy_appointment_time', 'referral_phone', 'contract_id', 'contract_no', 'econtract_code', 'econtract_id', 'completed_register_date', 'ordered_success_date', 'date_created', 'date_last_updated', 'next_tracking_time', 'created_at', 'updated_at'];
    
    public function contract() {
        return $this->belongsTo(Contracts::class, 'contract_no', 'contract_no');
    }
}
