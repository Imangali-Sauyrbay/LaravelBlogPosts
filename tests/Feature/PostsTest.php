<?php

namespace Tests\Feature;

use App\Models\Blogpost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_page_is_responding_correctly()
    {
        $this->get(route('posts.index'))->assertStatus(200);
    }

    public function test_message_if_there_no_posts()
    {
        $response = $this->get(route('posts.index'));
        $response->assertSeeText('Nothing posted yet!');
    }

    public function test_if_a_new_post_adding_is_working_correctly()
    {
        $post = Blogpost::create([
            'title' => 'new title',
            'content' => 'some content'
        ]);
        $post->setSlug()->save();

        $res = $this->get(route('posts.index'));
        $res->assertSeeTextInOrder([
            $post['title'],
            $post['content']
        ]);

        $this->assertDatabaseHas('blogposts', [
            'title'=> $post['title'],
            'content' => $post['content']
        ]);
    }

    public function test_store_endpoint_testing()
    {
        $data = [
            'title' => 'some new post',
            'content' => 'lorem ipsum dolore sit amet'
        ];

        $this->post(route('posts.store'), $data)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'new blogpost was created');

        $this->assertDatabaseHas('blogposts', $data);
    }

    public function test_store_endpoint_validation_test()
    {
        $data = [
            'title' => 'x',
            'content' => 'x'
        ];

        $this->post(route('posts.store'), $data)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $msgs = session('errors')->getMessages();

        $this->assertEquals($msgs['title'][0], 'The title must be at least 3 characters.');
        $this->assertEquals($msgs['content'][0], 'The content must be at least 3 characters.');
    }

    public function test_update_endpoint()
    {
        $data = [
            'title' => 'new title',
            'content' => 'some content'
        ];

        $new_data = [
            'title' => 'a new valid title',
            'content' => 'valid content'
        ];

        $post = Blogpost::create($data);
        $post->setSlug()->save();

        $this->assertDatabaseHas('blogposts', $data);

        $this->put(route('posts.update', ['post' => $post->slug]), $new_data)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'blogpost was updated');
        $this->assertDatabaseMissing('blogposts', $data);
        $this->assertDatabaseHas('blogposts', $new_data);
    }

    public function test_delete_endpoint()
    {
        $data = [
            'title' => 'new title',
            'content' => 'some content'
        ];

        $post = Blogpost::create($data);
        $post->setSlug()->save();

        $this->assertDatabaseHas('blogposts', $data);

        $this->delete(route('posts.destroy', ['post' => $post->slug]))
        ->assertStatus(302)
        ->assertSessionHas('status');

        $this->assertDatabaseMissing('blogposts', $data);
    }

}
