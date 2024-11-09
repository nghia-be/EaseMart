<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTransaction extends Model
{
    use HasFactory;
    protected $table = 'history_transaction';
    protected $primaryKey = 'id';
    protected $fillable = [
        'phone',
        'typr',
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
