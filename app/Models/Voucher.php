<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'vouchers';
    protected $primaryKey = 'id';
    protected $fillable = ['id','code','discount','start_date','end_date','usage_limit','usage_count'];
}
