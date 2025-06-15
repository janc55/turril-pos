<?php

namespace App\Policies;

use App\Models\CashBox;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CashBoxPolicy
{
    use HandlesAuthorization; // Usa este trait

    /**
     * Permite que los administradores tengan acceso total sin revisar cada permiso.
     */
    public function before(User $user, string $ability)
    {
        if ($user->hasRole('Administrador')) {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any CashBox records (acceso a la lista de cajas).
     */
    public function viewAny(User $user): bool
    {
        // Solo si el usuario tiene el permiso general para ver cajas.
        // El filtrado por sucursal para la lista se hará en el 'modifyQueryUsing' del CashBoxResource.
        return $user->hasPermissionTo('view_any_cash_box');
    }

    /**
     * Determine whether the user can view a specific CashBox record.
     */
    public function view(User $user, CashBox $cashBox): bool
    {
        // Primero, verifica si el usuario tiene el permiso general para ver una caja.
        if ($user->hasPermissionTo('view_cash_box')) {
            // Si el usuario es 'Cajero' o 'Gerente' y tiene una sucursal asignada,
            // solo puede ver cajas que pertenezcan a SU sucursal.
            if (($user->hasRole('Cajero') || $user->hasRole('Gerente')) && $user->branch_id) {
                return $cashBox->branch_id === $user->branch_id;
            }
            // Otros roles con permiso (ej. Almacenero, si tuviera este permiso)
            // pueden ver cualquier caja.
            return true;
        }
        // Si no tiene el permiso general, denegar.
        return false;
    }

    /**
     * Determine whether the user can create CashBox records.
     * La creación de nuevas cajas físicas suele ser una tarea de administrador o gerente.
     */
    public function create(User $user): bool
    {
        // Solo si el usuario tiene el permiso general para crear cajas.
        // Un cajero normalmente no crea una nueva caja física, sino que "abre" una existente.
        if ($user->hasPermissionTo('create_cash_box')) {
             // Si el usuario es 'Gerente' y tiene una sucursal asignada, podría crear una caja para SU sucursal.
            if ($user->hasRole('Gerente') && $user->branch_id) {
                return true; // Puede crear una caja para su sucursal. (La sucursal se seleccionará en el form)
            }
            // Administrador ya cubierto por before(). Otros roles que no sean gerente, si tienen el permiso, pueden crear.
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update a specific CashBox record.
     * La actualización de los detalles de una caja física.
     */
    public function update(User $user, CashBox $cashBox): bool
    {
        // Solo si el usuario tiene el permiso general para actualizar cajas.
        if ($user->hasPermissionTo('update_cash_box')) {
            // Si el usuario es 'Gerente' y tiene una sucursal asignada,
            // solo puede actualizar cajas que pertenezcan a SU sucursal.
            if ($user->hasRole('Gerente') && $user->branch_id) {
                return $cashBox->branch_id === $user->branch_id;
            }
            // Otros roles con permiso pueden actualizar cualquier caja.
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete a specific CashBox record.
     * La eliminación de cajas físicas es una acción altamente restrictiva.
     */
    public function delete(User $user, CashBox $cashBox): bool
    {
        // Generalmente, solo los administradores tienen este permiso.
        // Un gerente podría tenerlo si es muy necesario y bajo control estricto.
        return $user->hasPermissionTo('delete_cash_box');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CashBox $cashBox): bool
    {
        // No aplicable si no usas soft deletes para CashBox.
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CashBox $cashBox): bool
    {
        // No aplicable si no usas soft deletes para CashBox.
        return false;
    }
}
