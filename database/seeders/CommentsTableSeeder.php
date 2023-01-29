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

        if($authors->count() <= 0) {
            $this->command->error('There is no Authors, so comments won\'t be added!');
            return;
        }

        if($posts->count() <= 0) {
            $this->command->error('There is no Posts, so comments won\'t be added!');
            return;
        }

        $commentsCount = max((int) $this->command->ask('How many comments should be added?', 150), 0);

        Comment::factory($commentsCount)->make()
        ->each(function($comment) use($posts, $authors) {
            $comment['author_id'] = $authors->random()['id'];
            $comment['commentable_id'] = $posts->random()['id'];
            $comment['commentable_type'] = Blogpost::class;
            $comment->save();
        });

        Comment::factory($commentsCount)->make()
        ->each(function($comment) use($authors) {
            $comment['author_id'] = $authors->random()['id'];
            $comment['commentable_id'] = $authors->random()['id'];
            $comment['commentable_type'] = Author::class;
            $comment->save();
        });
    }
}
