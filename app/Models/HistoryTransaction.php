<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTransaction extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $table = 'history_transaction';
    protected $primaryKey = 'id';
    protected $fillable = [
        'phone',
        'type',
        'gateway',
        'payment_id',
        'txn_id',
        'payment_id',
        'content',
        'datetime',
        'balance',
        'number'
    ];

}
