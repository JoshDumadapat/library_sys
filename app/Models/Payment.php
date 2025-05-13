<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'trans_ID',
        'amount',
        'method',
        'collected_by',
        'payment_id',
    ];


    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'trans_ID');
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function fines()
    {
        return $this->hasMany(Fine::class, 'payment_id', 'payment_id');
    }
}
