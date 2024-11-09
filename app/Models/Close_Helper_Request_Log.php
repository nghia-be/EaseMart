<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class Close_Helper_Request_Log extends MY_Model
{
    protected $table = 'close_helper_request_log';
    protected $primaryKey = 'id';
    protected $fillable = ['contract_no','report_id', 'response', 'created_at'];

}
