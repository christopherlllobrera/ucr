<?php

namespace App\Policies;

use App\Models\User;
use App\Models\collection;
use Illuminate\Auth\Access\Response;

class CollectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        if ($user->hasPermissionTo('view-collection')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, collection $collection)
    {
        if ($user->hasPermissionTo('view-collection')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        if ($user->hasPermissionTo('create-collection')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, collection $collection)
    {
        if ($user->hasPermissionTo('update-collection')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, collection $collection)
    {
        if ($user->hasPermissionTo('delete-collection')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, collection $collection)
    {
        if ($user->hasPermissionTo('delete-collection')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, collection $collection)
    {
        if ($user->hasPermissionTo('delete-collection')) {
            return true;
        }
        return false;
    }
}
