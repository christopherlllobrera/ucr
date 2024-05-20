<?php

namespace App\Policies;

use App\Models\User;
use App\Models\draftbilldetails;
use Illuminate\Auth\Access\Response;

class DraftbilldetailsPolicy
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
    public function view(User $user, draftbilldetails $draftbilldetails)
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
    public function update(User $user, draftbilldetails $draftbilldetails)
    {
        if ($user->hasPermissionTo('update-draftbill')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, draftbilldetails $draftbilldetails)
    {
        if ($user->hasPermissionTo('delete-draftbill')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, draftbilldetails $draftbilldetails)
    {
        if ($user->hasPermissionTo('delete-draftbill')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, draftbilldetails $draftbilldetails)
    {
        if ($user->hasPermissionTo('delete-draftbill')) {
            return true;
        }
        return false;
    }
}
