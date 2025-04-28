<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $primaryKey = 'floor_id';
    protected $fillable = ['floor_num'];

    public function books()
    {
        return $this->hasMany(Book::class, 'floor_id');
    }
}
