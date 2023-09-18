<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperAuthor
 */
class Author extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function blogposts()
    {
        return $this->hasMany(Blogpost::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentsOn()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function defaultImageUrl()
    {
        return Storage::url('default-avatar.png');
    }

    public function defaultImagePath()
    {
        return Storage::drive('public')->path('default-avatar.png');
    }

    public function scopeWithMostBlogposts(Builder $query)
    {
        return $query->withCount('blogposts')->orderBy('blogposts_count', 'desc');
    }

    public function scopeWithMostBlogpostsLastMonth(Builder $query)
    {
        $filterFunction = function ($query) {
            $startDate = now()->subMonth();
            $endDate = now();

            $query->whereBetween('created_at', [$startDate, $endDate])
                  ->whereNull('deleted_at');
        };

        return $query->whereHas('blogposts', $filterFunction, '>=', 2)
        ->withCount(['blogposts' => $filterFunction])
        ->orderBy('blogposts_count', 'desc');
    }

    public static function boot() {
        parent::boot();
        $setRole = function (Author $author) {
            $user_role = Role::where('name', 'user')->first();

            if(!$author['role_id']){
                $author['role_id'] = $user_role->id;
            }

            return $author;
        };

        static::creating($setRole);
        static::saving($setRole);
        static::updating($setRole);
    }
}
