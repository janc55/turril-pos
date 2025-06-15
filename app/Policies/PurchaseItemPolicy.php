<?php

namespace App\Policies;

use App\Models\PurchaseItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PurchaseItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_purchase');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PurchaseItem $purchaseItem): bool
    {
        return $user->hasPermissionTo('view_purchase');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_purchase');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PurchaseItem $purchaseItem): bool
    {
        return $user->hasPermissionTo('update_purchase');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PurchaseItem $purchaseItem): bool
    {
        return $user->hasPermissionTo('delete_purchase');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PurchaseItem $purchaseItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PurchaseItem $purchaseItem): bool
    {
        return false;
    }
}
