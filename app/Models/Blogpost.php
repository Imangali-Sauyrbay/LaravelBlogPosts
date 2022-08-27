<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Normalizer\SlugNormalizer;

class Blogpost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'slug'
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
}
