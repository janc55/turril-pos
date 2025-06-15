<?php

namespace App\Policies;

use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SaleItemPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability)
    {
        if ($user->hasRole('Administrador')) {
            return true; // El administrador puede hacer cualquier cosa
        }
        // Si no es administrador, la ejecución continúa a los métodos específicos.
        // Si retorna null, la decisión recae en el método de la política.
        return null;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_sale_item');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SaleItem $saleItem): bool
    {
        if ($user->hasPermissionTo('view_sale_item')) {
            // Lógica para limitar por sucursal, si es necesario.
            // Asumiendo que SaleItem tiene una relación con Sale, y Sale tiene branch_id.
            if (($user->hasRole('Cajero') || $user->hasRole('Gerente')) && $user->branch_id) {
                // Si el usuario es cajero/gerente y tiene branch_id, solo puede ver
                // ítems de venta que pertenecen a ventas de SU sucursal.
                return $saleItem->sale->branch_id === $user->branch_id;
            }
            // Si no es cajero/gerente (ej. almacenero, admin), o no está limitado por sucursal.
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_sale');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SaleItem $saleItem): bool
    {
        return $user->hasPermissionTo('update_sale');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SaleItem $saleItem): bool
    {
        return $user->hasPermissionTo('delete_sale');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SaleItem $saleItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SaleItem $saleItem): bool
    {
        return false;
    }
}
