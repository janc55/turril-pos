<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class StockMovementPolicy
{
    use HandlesAuthorization; // Usa este trait

    /**
     * Permite que los administradores tengan acceso total sin revisar cada permiso.
     * Se ejecuta antes que cualquier otro método de la política.
     */
    public function before(User $user, string $ability)
    {
        // Si el usuario tiene el rol 'Administrador', puede realizar cualquier acción.
        if ($user->hasRole('Administrador')) {
            return true;
        }
        // Si no es administrador, la ejecución continúa a los métodos específicos de la política.
        return null;
    }

    /**
     * Determine whether the user can view any StockMovement records (acceso a la lista).
     */
    public function viewAny(User $user): bool
    {
        // Solo si el usuario tiene el permiso general para ver movimientos de stock.
        // El filtrado por sucursal para la lista se hará en el 'modifyQueryUsing' del StockMovementResource.
        return $user->hasPermissionTo('view_any_stock_movement');
    }

    /**
     * Determine whether the user can view a specific StockMovement record.
     */
    public function view(User $user, StockMovement $stockMovement): bool
    {
        // Primero, verifica si el usuario tiene el permiso general para ver un movimiento.
        if ($user->hasPermissionTo('view_stock_movement')) {
            // Si el usuario es 'Gerente' o 'Almacenero' y tiene una sucursal asignada,
            // solo puede ver movimientos que pertenezcan a SU sucursal.
            if (($user->hasRole('Gerente') || $user->hasRole('Almacenero')) && $user->branch_id) {
                return $stockMovement->branch_id === $user->branch_id;
            }
            // Si el usuario es otro rol (no administrador y no gerente/almacenero ligado a sucursal),
            // o no tiene branch_id asignado, puede ver cualquier movimiento si tiene el permiso.
            return true;
        }
        // Si no tiene el permiso general, denegar.
        return false;
    }

    /**
     * Determine whether the user can create StockMovement records.
     */
    public function create(User $user): bool
    {
        // Los almaceneros son los principales creadores de movimientos (entradas, ajustes, transferencias).
        // Los gerentes también podrían necesitar crear algunos tipos de movimientos.
        // Los cajeros crean 'exit' de forma indirecta al vender, pero quizás no directamente desde el resource.
        if ($user->hasPermissionTo('create_stock_movement')) {
            // Un usuario sin branch_id no debería poder crear un movimiento si es un rol ligado a sucursal.
            if (($user->hasRole('Gerente') || $user->hasRole('Almacenero')) && !$user->branch_id) {
                return false; // Gerente/Almacenero sin sucursal asignada no puede crear movimientos.
            }
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update a specific StockMovement record.
     */
    public function update(User $user, StockMovement $stockMovement): bool
    {
        // La actualización de movimientos de stock suele ser más restrictiva.
        // Un almacenero o gerente podría necesitar corregir un error.
        if ($user->hasPermissionTo('update_stock_movement')) {
            // Si el usuario es 'Gerente' o 'Almacenero' y tiene una sucursal asignada,
            // solo puede actualizar movimientos que pertenezcan a SU sucursal.
            if (($user->hasRole('Gerente') || $user->hasRole('Almacenero')) && $user->branch_id) {
                return $stockMovement->branch_id === $user->branch_id;
            }
            // Otros roles con permiso pueden actualizar cualquier movimiento.
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete a specific StockMovement record.
     * La eliminación de movimientos de stock debe ser MUY restringida, ya que afecta la auditoría.
     */
    public function delete(User $user, StockMovement $stockMovement): bool
    {
        // Generalmente, solo los administradores tienen este permiso.
        // Si un gerente tiene un permiso muy específico para eliminar movimientos de su sucursal,
        // podrías añadir esa lógica aquí.
        // Por ahora, lo mantenemos simple.
        return $user->hasPermissionTo('delete_stock_movement');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StockMovement $stockMovement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StockMovement $stockMovement): bool
    {
        return false;
    }
}
