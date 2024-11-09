<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class Ftel_Branch extends Model
{
    use HasFactory;
    protected $primaryKey = '';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'ftel_branch';
    protected $fillable = ['location_id', 'branch_code','branch_name'];
}
