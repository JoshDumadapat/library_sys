<?php

namespace App\Models;

use App\Models\Book;

use Illuminate\Database\Eloquent\Model;

class TransDetail extends Model
{

    protected $table = 'trans_details';
    protected $primaryKey = 'tdetail_ID';

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'trans_ID');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_ID');
    }

    public function fines()
    {
        return $this->hasMany(Fine::class, 'tdetail_ID');
    }
}
