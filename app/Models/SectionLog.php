<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SectionLog extends MY_Model
{
    use HasFactory;
    protected $table = 'section_log';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = [];
}
