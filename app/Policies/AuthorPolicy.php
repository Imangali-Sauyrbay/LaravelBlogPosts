<?php

namespace App\Policies;

use App\Models\Author;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Author $author)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Author  $author
     * @param  \App\Models\Author  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Author $author, Author $model)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Author $author)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Author  $author
     * @param  \App\Models\Author  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Author $author, Author $model)
    {
        return $author->id == $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Author  $author
     * @param  \App\Models\Author  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Author $author, Author $model)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Author  $author
     * @param  \App\Models\Author  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Author $author, Author $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Author  $author
     * @param  \App\Models\Author  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Author $author, Author $model)
    {
        return false;
    }
}
