<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $primaryKey = 'trans_ID'; // Ensure this is set correctly if not using default 'id'
    public $incrementing = true; // Ensure auto-increment is enabled
    protected $table = 'transactions'; // Ensure the correct table is referenced 

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID', 'id');
    }


    public function admin()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function transDetails()
    {
        return $this->hasMany(TransDetail::class, 'trans_ID');
    }

    public function fine()
    {
        return $this->hasOne(Fine::class, 'trans_ID', 'trans_ID');
    }
}
