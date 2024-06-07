<?php

namespace App\Policies;

use App\Models\User;
use App\Models\accrual;
use Illuminate\Auth\Access\Response;

class AccrualPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        if ($user->hasPermissionTo('view-any-accrual')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, accrual $accrual)
    {
        if ($user->hasPermissionTo('view-accrual')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        if ($user->hasPermissionTo('create-accrual')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, accrual $accrual)
    {
        if ($user->hasPermissionTo('update-accrual')) {
            return true;
        }
        return false;
    }

    public function EditAccrualsParkDoc(User $user, accrual $accrual) //Not working pa
    {
        if ($user->hasPermissionTo('update-accrual-park-doc')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, accrual $accrual)
    {
        if ($user->hasPermissionTo('delete-accrual')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, accrual $accrual)
    {
        if ($user->hasPermissionTo('delete-accrual')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, accrual $accrual)
    {
        if ($user->hasPermissionTo('delete-accrual')) {
            return true;
        }
        return false;
    }
}
