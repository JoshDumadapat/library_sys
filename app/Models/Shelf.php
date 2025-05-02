<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;

    // Define the primary key explicitly if it's not 'id'
    protected $primaryKey = 'shelf_id';

    // Mass assignable columns
    protected $fillable = ['shelf_code'];

    // Relationship with books
    public function books()
{
    return $this->hasMany(Book::class, 'shelf_id', 'shelf_id');
}

}
