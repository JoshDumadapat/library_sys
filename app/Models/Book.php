<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $primaryKey = 'book_id';

    protected $fillable = [
        'title',
        'isbn',
        'volume',
        'total_copies',
        'published_date',
        'floor_id',
        'shelf_id',
    ];
    
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author', 'book_id', 'author_id');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genre', 'book_id', 'genre_id');
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }

    public function shelf()
{
    return $this->belongsTo(Shelf::class, 'shelf_id', 'shelf_id');
}


// where i started for the lend 

public function transDetails()
{
    return $this->hasMany(TransDetail::class, 'book_id');
}

// Accessors
public function getLendedCopiesAttribute()
{
    return $this->transDetails()->where('td_status', 'borrowed')->count();
}

public function getAvailableCopiesAttribute()
{
    return $this->total_copies - $this->lended_copies;
}

public function getBookStatusAttribute()
{
    return $this->available_copies > 0 ? 'Available' : 'Unavailable';
}

}
