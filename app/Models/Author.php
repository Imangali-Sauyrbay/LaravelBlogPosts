<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Author
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $role_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Blogpost[] $blogposts
 * @property-read int|null $blogposts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Profile|null $profile
 * @property-read \App\Models\Role $role
 * @method static \Database\Factories\AuthorFactory factory(...$parameters)
 * @method static Builder|Author newModelQuery()
 * @method static Builder|Author newQuery()
 * @method static \Illuminate\Database\Query\Builder|Author onlyTrashed()
 * @method static Builder|Author query()
 * @method static Builder|Author whereCreatedAt($value)
 * @method static Builder|Author whereDeletedAt($value)
 * @method static Builder|Author whereEmail($value)
 * @method static Builder|Author whereEmailVerifiedAt($value)
 * @method static Builder|Author whereId($value)
 * @method static Builder|Author whereName($value)
 * @method static Builder|Author wherePassword($value)
 * @method static Builder|Author whereRememberToken($value)
 * @method static Builder|Author whereRoleId($value)
 * @method static Builder|Author whereUpdatedAt($value)
 * @method static Builder|Author withMostBlogposts()
 * @method static Builder|Author withMostBlogpostsLastMonth()
 * @method static \Illuminate\Database\Query\Builder|Author withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Author withoutTrashed()
 * @mixin \Eloquent
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
        return Storage::url('avatars/default.png');
    }

    public function scopeWithMostBlogposts(Builder $query)
    {
        return $query->withCount('blogposts')->orderBy('blogposts_count', 'desc');
    }

    public function scopeWithMostBlogpostsLastMonth(Builder $query)
    {
        return $query->withCount(['blogposts' => function (Builder $query) {
           return $query->whereBetween(static::CREATED_AT, [now()->subMonth(), now()]);
        }])->having('blogposts_count', '>=', 2)->orderBy('blogposts_count', 'desc');
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
