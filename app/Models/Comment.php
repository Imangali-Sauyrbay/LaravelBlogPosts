<?php

namespace App\Models;

use App\Traits\TaggableTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * @mixin IdeHelperComment
 */
class Comment extends Model
{
    use HasFactory, TaggableTrait;

    protected $fillable = [
        'content',
        'author_id',
    ];

    public function commentable()
    {
        return $this->morphTo();
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
            if($comment->commentable_type == Blogpost::class) {
                $post = Blogpost::find($comment->commentable_id);
                Cache::tags(['blogpost'])->forget("blog-post-{$post->slug}");
                Cache::tags(['blogpost', 'side_bar'])->forget('most_commented_of_all_time');
            }
        });
    }
}
