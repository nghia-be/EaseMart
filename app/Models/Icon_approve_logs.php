<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

use App\Admin\UserController;

class Icon_approve_logs extends MY_Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'icon_approve_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['product_type','product_id', 'updated_by', 'approved_type', 'approved_status', 'approved_by', 'approved_at', 'created_at', 'updated_at'];

    public function user() {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
