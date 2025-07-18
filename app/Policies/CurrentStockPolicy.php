<?php

namespace App\Policies;

use App\Models\CurrentStock;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CurrentStockPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_current_stock');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CurrentStock $currentStock): bool
    {
        return $user->hasPermissionTo('view_current_stock');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_current_stock');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CurrentStock $currentStock): bool
    {
        return $user->hasPermissionTo('update_current_stock');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CurrentStock $currentStock): bool
    {
        return $user->hasPermissionTo('delete_current_stock');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CurrentStock $currentStock): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CurrentStock $currentStock): bool
    {
        return false;
    }
}
