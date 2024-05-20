<?php

namespace App\Policies;

use App\Models\User;
use App\Models\draftbill;
use Illuminate\Auth\Access\Response;

class DraftbillPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        if ($user->hasPermissionTo('view-draftbill')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, draftbill $draftbill)
    {
        if ($user->hasPermissionTo('view-draftbill')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        if ($user->hasPermissionTo('create-draftbill')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, draftbill $draftbill)
    {
        if ($user->hasPermissionTo('update-draftbill')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, draftbill $draftbill)
    {
        if ($user->hasPermissionTo('delete-draftbill')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, draftbill $draftbill)
    {
        if ($user->hasPermissionTo('delete-draftbill')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, draftbill $draftbill)
    {
        if ($user->hasPermissionTo('delete-draftbill')) {
            return true;
        }
        return false;
    }
}
