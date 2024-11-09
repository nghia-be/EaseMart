<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log_Report extends Model
{
    protected $table = 'log_report';
    protected $primaryKey = 'id';
    protected $fillable = ['date_report','data','source','report_type_id'];
}
