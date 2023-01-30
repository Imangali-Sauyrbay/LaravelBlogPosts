<?php

namespace App\Models;

use App\Traits\TaggableTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * App\Models\Comment
 *
 * @property int $id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $author_id
 * @property int $blogpost_id
 * @property-read \App\Models\Author $author
 * @property-read \App\Models\Blogpost $blogpost
 * @method static \Database\Factories\CommentFactory factory(...$parameters)
 * @method static Builder|Comment latest()
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereAuthorId($value)
 * @method static Builder|Comment whereBlogpostId($value)
 * @method static Builder|Comment whereContent($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @mixin \Eloquent
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
