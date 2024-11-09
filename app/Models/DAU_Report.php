<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DAU_Report extends Model
{
    protected $table = "dau_report";
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'location_zone',
        'location_code',
        'branch_name',
        'count_login',
        'from_date',
        'to_date',
        'type',
        'created_at'
    ];
}
