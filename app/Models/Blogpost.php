<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public static function boot() {
        parent::boot();

        $setSlug = fn (Blogpost $post) => $post->setSlug();

        static::creating($setSlug);
        static::saving($setSlug);
        static::updating($setSlug);
    }
}
