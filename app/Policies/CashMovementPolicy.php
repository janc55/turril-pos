<?php

namespace App\Policies;

use App\Models\CashMovement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CashMovementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_cash_movement');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CashMovement $cashMovement): bool
    {
        return $user->hasPermissionTo('view_cash_movement');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_cash_movement');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CashMovement $cashMovement): bool
    {
        return $user->hasPermissionTo('update_cash_movement');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CashMovement $cashMovement): bool
    {
        return $user->hasPermissionTo('delete_cash_movement');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CashMovement $cashMovement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CashMovement $cashMovement): bool
    {
        return false;
    }
}
