<?php

namespace App\Models\Scopes;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class ShowDeletedToAdmin implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check() && Role::isAdmin(Auth::user()->role_id)) {
            // $builder->withTrashed();
            $builder->withoutGlobalScope('Illuminate\Database\Eloquent\SoftDeletingScope');
        }
    }

}
