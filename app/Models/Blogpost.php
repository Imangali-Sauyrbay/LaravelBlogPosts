<?php

namespace App\Models;

use App\Models\Scopes\ShowDeletedToAdmin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use League\CommonMark\Normalizer\SlugNormalizer;

class Blogpost extends Model
{
    use HasFactory, SoftDeletes;

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
        return (new SlugNormalizer())->normalize($str) . uniqid('-');
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeWithRelations(Builder $query)
    {
        return $query->with(['comments', 'comments.author', 'tags', 'author']);
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
        static::updating($setSlug);
        static::deleting(function(Blogpost $post) {
            Cache::tags(['blogpost'])->forget("blog-post-{$post->slug}");
        });
        static::updating(function(Blogpost $post) {
            Cache::tags(['blogpost'])->forget("blog-post-{$post->slug}");
        });
    }
}
