<?php

namespace App\Observers;

use App\Models\StockMovement;
use App\Models\CurrentStock; // Asegúrate de importar tu modelo CurrentStock

class StockMovementObserver
{
    /**
     * Handle the StockMovement "created" event.
     * Se ejecuta cuando un nuevo movimiento de stock es creado.
     */
    public function created(StockMovement $stockMovement): void
    {
        $this->updateCurrentStock($stockMovement, 'add');
    }

    /**
     * Handle the StockMovement "deleted" event.
     * Se ejecuta si un movimiento de stock es eliminado (para revertir el cambio).
     * Ten cuidado con eliminar movimientos en un sistema real; a menudo es mejor hacer un ajuste
     * para corregir, en lugar de borrar el historial.
     */
    public function deleted(StockMovement $stockMovement): void
    {
        $this->updateCurrentStock($stockMovement, 'subtract'); // Revertir el cambio
    }

    /**
     * Función de ayuda para actualizar el CurrentStock basado en un StockMovement.
     * @param StockMovement $stockMovement El registro de movimiento que causó el cambio.
     * @param string $operation 'add' o 'subtract' para indicar si sumar o restar al CurrentStock.
     */
    private function updateCurrentStock(StockMovement $stockMovement, string $operation): void
    {
        // Determinar qué ID está presente (product_id o ingredient_id)
        $itemId = $stockMovement->product_id ?? $stockMovement->ingredient_id;
        $itemTypeColumn = $stockMovement->product_id ? 'product_id' : 'ingredient_id';

        // Buscar el registro de CurrentStock o crear una nueva instancia si no existe
        $currentStock = CurrentStock::firstOrNew([
            'branch_id' => $stockMovement->branch_id,
            $itemTypeColumn => $itemId,
        ]);

        // Asegurarse de que la cantidad inicial sea 0 si es un nuevo registro
        if (!$currentStock->exists) {
            $currentStock->quantity = 0;
        }

        // Ajustar la cantidad basada en el tipo de movimiento y la operación
        $movementQuantity = $stockMovement->quantity; // La cantidad del movimiento siempre debería ser positiva aquí

        switch ($stockMovement->type) {
            case 'entry':
            case 'transfer_in':
                if ($operation === 'add') { // Cuando se crea el movimiento
                    $currentStock->quantity += $movementQuantity;
                } else { // Cuando se elimina el movimiento (revertir)
                    $currentStock->quantity -= $movementQuantity;
                }
                break;
            case 'exit':
            case 'transfer_out':
                if ($operation === 'add') { // Cuando se crea el movimiento
                    $currentStock->quantity -= $movementQuantity;
                } else { // Cuando se elimina el movimiento (revertir)
                    $currentStock->quantity += $movementQuantity;
                }
                break;
            case 'adjustment':
                // Para ajustes, la 'quantity' en stock_movements debería indicar el cambio neto.
                // Es decir, si fue un aumento, quantity es positivo. Si fue una disminución, quantity es negativo.
                // Si siempre guardas quantity como positivo y usas otra columna para el signo, ajusta aquí.
                if ($operation === 'add') { // Cuando se crea el movimiento
                    $currentStock->quantity += $movementQuantity;
                } else { // Cuando se elimina el movimiento (revertir)
                    $currentStock->quantity -= $movementQuantity;
                }
                break;
        }

        // Opcional pero recomendado: Asegurar que la cantidad no sea negativa (o manejar stock negativo si es el caso de tu negocio)
        $currentStock->quantity = max(0, $currentStock->quantity);

        // Guardar los cambios en CurrentStock
        $currentStock->save();
    }
}
