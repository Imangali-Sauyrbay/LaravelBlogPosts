<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
    use HasFactory;

    public function blogpost()
    {
        return $this->belongsTo(Blogpost::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
