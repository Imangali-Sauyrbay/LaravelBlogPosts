<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    public function role()
    {
        return $this->belongsTo(Role::class);
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
