<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

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
}
