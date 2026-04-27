<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'points',
        'type',
        'status',
        'reference_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
