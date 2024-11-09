<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

use App\Admin\UserController;

class Hidepayments extends MY_Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hidepayments';
    protected $primaryKey = 'id';
    protected $fillable = ['version','isUpStoreAndroid', 'isUpStoreIos', 'api_status', 'error_mesg', 'deleted_at', 'updated_by', 'created_by'];

    public function user() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
