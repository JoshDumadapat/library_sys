<?php

// app/Models/Fine.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    protected $primaryKey = 'fine_id';

    protected $fillable = [
        'tdetail_ID', 
        'fine_amt',
        'reason',
        'fine_status',
        'collected_by',
        'ftype_id',
    ];

    const REASON_OVERDUE = 'overdue';
    const REASON_MISSING = 'missing';
    const REASON_DAMAGED = 'damaged';
    const REASON_RETURNED = 'returned';

    public static function getReasons()
    {
        return [
            self::REASON_OVERDUE,
            self::REASON_MISSING,
            self::REASON_DAMAGED,
            self::REASON_RETURNED
        ];
    }

    // Relationships
    public function transactionDetail()
    {
        return $this->belongsTo(TransDetail::class, 'tdetail_ID');
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function fineType()
    {
        return $this->belongsTo(FineType::class, 'ftype_id');
    }
}
