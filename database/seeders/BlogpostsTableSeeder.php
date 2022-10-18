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

        if($authors->count() <= 0) {
            $this->command->error('There is no Authors, so posts won\'t be added!');
            return;
        }

        $postsCount = max((int) $this->command->ask('How many posts should be added?', 50), 0);

        Blogpost::factory($postsCount)->make()
        ->each(function($post) use($authors) {
            $post['author_id'] = $authors->random()['id'];
            $post->save();
        });
    }
}
