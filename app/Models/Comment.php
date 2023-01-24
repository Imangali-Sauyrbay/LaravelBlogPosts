<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'author_id',
        'blogpost_id',
    ];

    public function blogpost()
    {
        return $this->belongsTo(Blogpost::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function(Comment $comment) {
            Cache::tags(['blogpost'])->forget("blog-post-{$comment->blogpost->slug}");
            Cache::tags(['blogpost'])->forget('most_commented_of_all_time');
        });
    }
}
