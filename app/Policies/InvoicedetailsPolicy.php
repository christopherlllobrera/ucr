<?php

namespace App\Policies;

use App\Models\User;
use App\Models\invoicedetails;
use Illuminate\Auth\Access\Response;

class InvoicedetailsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        if ($user->hasPermissionTo('view-invoice')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, invoicedetails $invoicedetails)
    {
        if ($user->hasPermissionTo('view-invoice')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        if ($user->hasPermissionTo('create-invoice')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, invoicedetails $invoicedetails)
    {
        if ($user->hasPermissionTo('update-invoice')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, invoicedetails $invoicedetails)
    {
        if ($user->hasPermissionTo('delete-invoice')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, invoicedetails $invoicedetails)
    {
        if ($user->hasPermissionTo('delete-invoice')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, invoicedetails $invoicedetails)
    {
        if ($user->hasPermissionTo('delete-invoice')) {
            return true;
        }
        return false;
    }
}
