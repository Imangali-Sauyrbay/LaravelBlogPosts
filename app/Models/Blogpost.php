<?php

namespace App\Models;

use App\Models\Scopes\ShowDeletedToAdmin;
use App\Traits\TaggableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use League\CommonMark\Normalizer\SlugNormalizer;

/**
 * App\Models\Blogpost
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $author_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Author $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Database\Factories\BlogpostFactory factory(...$parameters)
 * @method static Builder|Blogpost latest()
 * @method static Builder|Blogpost mostCommented()
 * @method static Builder|Blogpost newModelQuery()
 * @method static Builder|Blogpost newQuery()
 * @method static \Illuminate\Database\Query\Builder|Blogpost onlyTrashed()
 * @method static Builder|Blogpost query()
 * @method static Builder|Blogpost whereAuthorId($value)
 * @method static Builder|Blogpost whereContent($value)
 * @method static Builder|Blogpost whereCreatedAt($value)
 * @method static Builder|Blogpost whereDeletedAt($value)
 * @method static Builder|Blogpost whereId($value)
 * @method static Builder|Blogpost whereSlug($value)
 * @method static Builder|Blogpost whereTitle($value)
 * @method static Builder|Blogpost whereUpdatedAt($value)
 * @method static Builder|Blogpost withRelCommCoutLatest()
 * @method static Builder|Blogpost withRelations()
 * @method static \Illuminate\Database\Query\Builder|Blogpost withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Blogpost withoutTrashed()
 * @mixin \Eloquent
 */
class Blogpost extends Model
{
    use HasFactory, SoftDeletes, TaggableTrait;

    protected $fillable = [
        'title',
        'content',
        'slug',
        'author_id'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlug()
    {
        $this->slug = static::getSlug($this->title);
        return $this;
    }

    public static function getSlug(string $str) {
        return (new SlugNormalizer())->normalize(mb_strcut($str, 0, 48)) . uniqid('-');
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeWithRelations(Builder $query)
    {
        return $query->with(['comments', 'image','comments.author', 'comments.author.image','tags', 'author']);
    }

    public function scopeWithRelCommCountLatest(Builder $query)
    {
        return $query->withRelations()->latest()->withCount('comments');
    }

    public function scopeMostCommented(Builder $query) {
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public static function boot() {
        static::addGlobalScope(new ShowDeletedToAdmin);
        parent::boot();

        $setSlug = fn (Blogpost $post) => $post->setSlug();

        static::creating($setSlug);
        static::saving($setSlug);

        static::deleting(function(Blogpost $post) {
            Cache::tags(['blogpost'])->forget("blog-post-{$post->slug}");
            Cache::tags(['blogpost', 'side_bar'])->flush();
        });

        static::updating(function(Blogpost $post) {
            $post->setSlug();
            Cache::tags(['blogpost'])->forget("blog-post-{$post->slug}");
            Cache::tags(['blogpost', 'side_bar'])->flush();
        });
    }
}
