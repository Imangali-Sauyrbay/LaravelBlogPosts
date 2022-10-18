<?php

namespace App\Policies;

use App\Models\Author;
use App\Models\Blogpost;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    use HandlesAuthorization;

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
