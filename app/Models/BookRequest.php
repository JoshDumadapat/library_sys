<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    protected $primaryKey = 'request_id';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_ID');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }


    //new added
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeSearch($query, $term)
    {
        return $query->whereHas('book', function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('author', 'like', "%{$term}%")
                ->orWhere('isbn', 'like', "%{$term}%");
        });
    }
}
