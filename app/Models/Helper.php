<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    protected $table = 'helper';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'description', 'solve_way', 'solve_time_avg', 'from', 'error_type', 'reason', 'created_at', 'updated_at', 'updated_by', 'created_by'];
}
