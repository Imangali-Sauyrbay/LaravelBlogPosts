<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperProfile
 */
class Profile extends Model
{
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
