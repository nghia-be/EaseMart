<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class Customer_Locations extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_location_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'customer_locations';
    protected $fillable = ['customer_location_id', 'location_code','location_name', 'location_zone', 'date_created', 'date_modified', 'is_deleted', ''];
}
