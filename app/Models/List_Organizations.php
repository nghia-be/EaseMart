<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Awobaz\Compoships\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class List_Organizations extends Model
{
    use HasFactory;
    // protected $connection = 'mysql2';
    protected $table = 'list_organizations';
    protected $primaryKey = 'id';
    protected $fillable = ['displayName','code','parentCode','codePath','namePath','isActive','location','zone_name','branch_name','branch_code','branch_name_code','branch_code_detail','department_code','department_name'];

    public function hdi_orders() {
        return $this->hasManyThrough(Employees::class, Hdi_Orders::class);
    }
}
