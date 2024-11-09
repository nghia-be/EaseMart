<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Awobaz\Compoships\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class Contracts extends Model
{
    protected $connection = 'mysql4';
    protected $table = 'contracts';
    protected $primaryKey = 'contract_id';
    public $timestamps = false;
    protected $fillable = ['contract_id', 'contract_no', 'contract_phone', 'local_type', 'full_name', 'first_access', 'email', 'address', 'location', 'location_id', 'location_code', 'location_name', 'location_zone', 'branch_code', 'branch_name', 'is_net_tv', 'gender', 'birthday', 'date_created', 'date_modified', 'public_notify_seen', 'public_notify_deleted', 'e_contract_confirmed', 'latitude', 'longitude', 'is_fpt_employee'];

}
