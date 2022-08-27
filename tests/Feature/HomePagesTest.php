<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomePagesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_home_page_is_responding_correctly()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_contacts_page_is_responding_correctly()
    {
        $response = $this->get(route('contacts'));
        $response->assertStatus(200);
    }
}
