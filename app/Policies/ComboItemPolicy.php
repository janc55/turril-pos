<?php

namespace App\Policies;

use App\Models\ComboItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ComboItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_combo_item');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ComboItem $comboItem): bool
    {
        return $user->hasPermissionTo('view_combo_item');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_combo_item');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ComboItem $comboItem): bool
    {
        return $user->hasPermissionTo('update_combo_item');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ComboItem $comboItem): bool
    {
        return $user->hasPermissionTo('delete_combo_item');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ComboItem $comboItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ComboItem $comboItem): bool
    {
        return false;
    }
}
