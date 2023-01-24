<?php

namespace App\Http\Composers;

use App\Models\Author;
use App\Models\Blogpost;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class ActivityComposer {

    public function compose(View $view)
    {
        $mostCommented = Cache::tags(['blogpost'])->remember('most_commented_of_all_time',
        now()->addHour(), fn() => Blogpost::mostCommented()->take(5)->get());

        $mostActiveAllTime = Cache::tags(['blogpost'])->remember('most_active_authors_of_all_time',
        now()->addHour(), fn() => Author::withMostBlogposts()->take(5)->get());

        $mostActiveLastMonth = Cache::tags(['blogpost'])->remember('most_active_authors_of_last_month',
        now()->addHour(), fn() => Author::withMostBlogpostsLastMonth()->take(5)->get());

        $view->with('most_commented_of_all_time', $mostCommented);
        $view->with('most_active_authors_of_all_time', $mostActiveAllTime);
        $view->with('most_active_authors_of_last_month', $mostActiveLastMonth);
    }
}
