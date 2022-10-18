<?php

namespace Tests\Feature;

use App\Models\Author;
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

        $data = $this->getBlogpostWithAuthor();

        $post = $data['post'];

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

        $this->actingAs($this->getAuthor())
            ->post(route('posts.store'), $data)
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

        $this->actingAs($this->getAuthor())
            ->post(route('posts.store'), $data)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $msgs = session('errors')->getMessages();

        $this->assertEquals($msgs['title'][0], 'The title must be at least 3 characters.');
        $this->assertEquals($msgs['content'][0], 'The content must be at least 3 characters.');
    }

    public function test_update_endpoint()
    {
        $user = $this->getAuthor();

        $data = [
            'title' => 'new title',
            'content' => 'some content',
            'author_id' => $user->id
        ];

        $new_data = [
            'title' => 'a new valid title',
            'content' => 'valid content'
        ];

        $post = Blogpost::create($data);

        $this->assertDatabaseHas('blogposts', $data);

        $this->actingAs($user)
            ->put(route('posts.update', ['post' => $post->slug]), $new_data)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'blogpost was updated');
        $this->assertDatabaseMissing('blogposts', $data);
        $this->assertDatabaseHas('blogposts', $new_data);
    }

    public function test_delete_endpoint()
    {
        $user = $this->getAuthor();

        $data = [
            'title' => 'new title',
            'content' => 'some content',
            'author_id' => $user->id
        ];

        $post = Blogpost::create($data);


        $this->assertDatabaseHas('blogposts', $data);

        $this->actingAs($user)
            ->delete(route('posts.destroy', ['post' => $post->slug]))
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertSoftDeleted('blogposts', $data);
    }

    private function getBlogpostWithAuthor()
    {
        $author = $this->getAuthor();

        $post = Blogpost::factory()->make([
            'title' => 'new title',
            'content' => 'some content'
        ]);

        $post['author_id'] = $author['id'];
        $post->save();

        return [
            'author' => $author,
            'post' => $post
        ];
    }

    private function getAuthor()
    {
        return Author::factory()->create();
    }

}
