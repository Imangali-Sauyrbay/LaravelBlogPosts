<?php

namespace App\Policies;

use App\Models\Author;
use App\Models\Blogpost;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(Author $user, Blogpost $post)
    {
        return $user->id == $post->author()->first()->id
                ? Response::allow()
                : Response::deny('You do not own this post.');
    }
}
