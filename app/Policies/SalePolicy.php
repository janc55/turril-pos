<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SalePolicy
{
    use HandlesAuthorization; // Usa este trait para helper methods like deny()

    /**
     * Permite que los administradores tengan acceso total sin revisar cada permiso.
     * Este método se ejecuta antes que cualquier otro método de la política.
     */
    public function before(User $user, string $ability)
    {
        // Si el usuario tiene el rol 'Administrador', puede realizar cualquier acción.
        // Esto simplifica enormemente la lógica de las políticas para los administradores.
        if ($user->hasRole('Administrador')) {
            return true;
        }
        // Si no es administrador, la ejecución continúa a los métodos específicos de la política.
        // Retornar 'null' permite que la decisión recaiga en el método de la política.
        return null;
    }

    /**
     * Determine whether the user can view any sales (acceso a la lista de ventas).
     */
    public function viewAny(User $user): bool
    {
        // Los cajeros, gerentes y almacenacero generalmente necesitan ver ventas.
        // Aseguramos que tengan el permiso general.
        return $user->hasPermissionTo('view_any_sale');

        // NOTA: El filtrado por sucursal para la lista 'viewAny'
        // se hará en el 'modifyQueryUsing' del SaleResource, no aquí.
        // Aquí solo determinamos si *puede* ver la lista, no *cuáles* elementos.
    }

    /**
     * Determine whether the user can view a specific sale.
     */
    public function view(User $user, Sale $sale): bool
    {
        // Primero, verifica si el usuario tiene el permiso general para ver ventas.
        if ($user->hasPermissionTo('view_sale')) {
            // Si el usuario es 'Cajero' o 'Gerente' y tiene una sucursal asignada,
            // solo puede ver ventas que pertenezcan a SU sucursal.
            if (($user->hasRole('Cajero') || $user->hasRole('Gerente')) && $user->branch_id) {
                return $sale->branch_id === $user->branch_id;
            }
            // Si el usuario es otro rol (ej. Almacenero, si tuviera permiso)
            // o si es un Administrador (ya cubierto por el método 'before'),
            // o si el usuario no tiene una sucursal asignada (lo cual debería ser revisado),
            // puede ver la venta.
            return true;
        }
        // Si no tiene el permiso 'view_sale', denegar.
        return false;
    }

    /**
     * Determine whether the user can create sales.
     */
    public function create(User $user): bool
    {
        // Los cajeros son los principales creadores de ventas.
        // Los gerentes también podrían necesitar crear ventas.
        // Además de tener el permiso, podríamos añadir una restricción:
        // solo pueden crear ventas si están asociados a una sucursal.
        if ($user->hasPermissionTo('create_sale')) {
            // Un usuario sin branch_id no debería poder crear una venta de sucursal.
            if (($user->hasRole('Cajero') || $user->hasRole('Gerente')) && !$user->branch_id) {
                return false; // Cajero/Gerente sin sucursal asignada no puede crear venta
            }
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update a specific sale.
     */
    public function update(User $user, Sale $sale): bool
    {
        // Los gerentes y cajeros (en ciertas circunstancias) podrían actualizar ventas.
        if ($user->hasPermissionTo('update_sale')) {
            // Si el usuario es 'Cajero' o 'Gerente' y tiene una sucursal asignada,
            // solo puede actualizar ventas que pertenezcan a SU sucursal.
            if (($user->hasRole('Cajero') || $user->hasRole('Gerente')) && $user->branch_id) {
                return $sale->branch_id === $user->branch_id;
            }
            // Otros roles con permiso pueden actualizar cualquier venta.
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete a specific sale.
     */
    public function delete(User $user, Sale $sale): bool
    {
        // La eliminación de ventas suele ser una acción más restrictiva.
        // Quizás solo los administradores o gerentes de esa sucursal puedan hacerlo.
        if ($user->hasPermissionTo('delete_sale')) {
            // Si el usuario es 'Gerente' y tiene una sucursal asignada,
            // solo puede eliminar ventas de SU sucursal.
            if ($user->hasRole('Gerente') && $user->branch_id) {
                return $sale->branch_id === $user->branch_id;
            }
            // Si no es gerente o es administrador (cubierto por 'before'),
            // puede eliminar.
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sale $sale): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sale $sale): bool
    {
        return false;
    }
}
