<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Blogpost;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Blogpost::all();
        $authors = Author::all();

        $comments = Comment::factory(150)->make()
        ->each(function($comment) use($posts, $authors) {
            $comment['author_id'] = $authors->random()['id'];
            $comment['blogpost_id'] = $posts->random()['id'];
            $comment->save();
        });


    }
}
