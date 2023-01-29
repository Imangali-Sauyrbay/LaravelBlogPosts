<?php

namespace App\Policies;

use App\Models\Author;
use App\Models\Blogpost;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogpostPolicy
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
        return $user->id == $post->author_id
                ? Response::allow()
                : Response::deny('You do not own this post.');
    }

    public function delete(Author $user, Blogpost $post)
    {
        return $this->update($user, $post);
    }
}
