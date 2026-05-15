<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookDownload extends Model
{
    public $timestamps = false;
    protected $fillable = ['book_id', 'user_id', 'ip_address', 'downloaded_at'];
    protected $casts = ['downloaded_at' => 'datetime'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
