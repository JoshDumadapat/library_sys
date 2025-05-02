<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FineType extends Model
{
    use HasFactory;

    protected $primaryKey = 'ftype_id';
    protected $fillable = [
        'reason',
        'default_amount',
        'is_per_day',
    ];

    public function fines()
{
    return $this->hasMany(Fine::class, 'ftype_id');
}

}
