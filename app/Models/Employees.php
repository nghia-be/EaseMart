<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Awobaz\Compoships\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class Employees extends Model
{
    protected $connection = 'mysql';
    protected $table = 'employees';
    protected $primaryKey = 'id';
    protected $fillable = ['id','name','full_name','phone','location_id','branch_code', 'description', 'organizationCode', 'code', 'emailAddress', 'organizationCode', 'organizationCodePath', 'checkUpdate', 'location', 'employee_code', 'organizationNamePath', 'isActive', 'dept_id', 'dept_name_1', 'dept_name_2', 'updated_from', 'branch_name', 'updated_by'];

    public function hdi_orders() {
        return $this->hasMany(Hdi_Orders::class, 'referral_phone', 'phone');
    }

    public function customer_locations() {
        return $this->hasOne(Customer_Locations::class, 'customer_location_id', 'location_id');
    }

    public function ftel_branch() {
        $relation = $this->hasMany(Ftel_Branch::class, ['location_id', 'branch_code'], ['location_id', 'branch_code']);
        return $relation;
    }

    public function list_organizations() {
        return $this->hasOne(List_Organizations::class, 'code', 'organizationCode');
    }

    public function group_by_list_organizations() {
        return $this->hasOne(List_Organizations::class, 'code', 'organizationCode')
                    // ->where('organizationCode', 'App\\Models\\Conversation')
                    ->groupBy('code');
    }
}
