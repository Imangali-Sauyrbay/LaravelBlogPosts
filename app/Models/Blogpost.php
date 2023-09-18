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
 * @mixin IdeHelperBlogpost
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
