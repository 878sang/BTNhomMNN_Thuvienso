<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'file_path',
        'description',
        'category_id',
        'author_id',
        'publisher_id',
        'price_points',
        'user_id',
        'status',
        'view_count',
        'download_count',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function buyers()
    {
        return $this->belongsToMany(User::class, 'book_user')->withPivot('price_paid')->withTimestamps();
    }

    public function favoritedBy()
    {
        return $this->hasMany(Favorite::class);
    }

    public function views()
    {
        return $this->hasMany(BookView::class);
    }

    public function downloads()
    {
        return $this->hasMany(BookDownload::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('stars') ?: 0;
    }
}
