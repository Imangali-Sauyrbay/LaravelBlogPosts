<?php

namespace App\Traits;

use App\Models\Tag;

trait TaggableTrait {

    public static $tagsRegex = "/@([^@]+)@/m";

    protected static function bootTaggableTrait() {
        static::updating(function ($model)
        {
            $model->tags()->sync(static::findTagsInContent($model->content));
        });

        static::created(function ($model)
        {
            $model->tags()->sync(static::findTagsInContent($model->content));
        });
    }

    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable')->withTimestamps();
    }

    private static function findTagsInContent($content) {
        preg_match_all(static::$tagsRegex, $content, $tags);
        return Tag::whereIn('name', $tags[1] ?? [])->get();
    }

    public static function hideTags($models) {
        foreach($models as $model) {
            $model->content = trim(preg_replace(static::$tagsRegex, '', $model->content));
        }
    }
}
