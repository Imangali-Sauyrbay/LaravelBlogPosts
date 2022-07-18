<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        mb_regex_encoding('UTF-8');
        $str = mb_ereg_replace('[^A-Za-z0-9А-Яа-яЁё\- ]+', '', $str);
        return Str::lower(mb_ereg_replace('\s+', '-', $str)) . uniqid('-', true);
    }

}
