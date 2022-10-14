<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Blogpost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogpostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authors = Author::all();

        $posts = Blogpost::factory(50)->make()
        ->each(function($post) use($authors) {
            $post['author_id'] = $authors->random()['id'];
            $post->save();
        });


    }
}
