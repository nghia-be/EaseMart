<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_Groups extends MY_Model
{
    use SoftDeletes;
    protected $table = 'user_groups';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id','group_id','deleted_at','updated_by','created_by'];
}
