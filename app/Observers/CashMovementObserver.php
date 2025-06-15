<?php

namespace App\Observers;

use App\Models\CashMovement;
use App\Models\CashBox; // Asegúrate de importar tu modelo CashBox

class CashMovementObserver
{
    /**
     * Handle the CashMovement "created" event.
     * Se ejecuta cuando un nuevo movimiento de caja es creado.
     */
    public function created(CashMovement $cashMovement): void
    {
        $this->updateCashBoxBalance($cashMovement, 'add');
    }

    /**
     * Handle the CashMovement "deleted" event.
     * Se ejecuta si un movimiento de caja es eliminado (para revertir el cambio en el balance).
     * NOTA: Eliminar movimientos de caja puede afectar la auditoría. Es preferible un 'ajuste'
     * o un 'retiro' para corregir errores en un sistema real.
     */
    public function deleted(CashMovement $cashMovement): void
    {
        $this->updateCashBoxBalance($cashMovement, 'subtract'); // Revertir el cambio
    }

    /**
     * Función de ayuda para actualizar el balance de la CashBox basado en un CashMovement.
     * @param CashMovement $cashMovement El registro de movimiento que causó el cambio.
     * @param string $operation 'add' o 'subtract' para indicar si sumar o restar al CurrentBalance.
     */
    private function updateCashBoxBalance(CashMovement $cashMovement, string $operation): void
    {
        // Busca la caja asociada al movimiento.
        $cashBox = CashBox::find($cashMovement->cash_box_id);

        if (!$cashBox) {
            // Esto no debería ocurrir si las claves foráneas están configuradas correctamente,
            // pero es una buena práctica de seguridad.
            return;
        }

        // El 'amount' del movimiento.
        $amount = $cashMovement->amount;

        // Determina si el tipo de movimiento es una entrada (positivo) o una salida (negativo)
        // según los tipos de ENUM que tienes en tu migración de cash_movements.
        $isIncome = in_array($cashMovement->type, ['deposit', 'sale_income', 'other_income']);
        $isExpense = in_array($cashMovement->type, ['withdrawal', 'purchase_payment', 'other_expense']);

        // Ajusta el balance de la caja.
        if ($operation === 'add') { // Cuando se crea un nuevo movimiento
            if ($isIncome) {
                $cashBox->current_balance += $amount;
            } elseif ($isExpense) {
                $cashBox->current_balance -= $amount;
            }
        } else { // Cuando se elimina un movimiento (revertir el efecto original)
            if ($isIncome) {
                $cashBox->current_balance -= $amount; // Si sumó, ahora resta
            } elseif ($isExpense) {
                $cashBox->current_balance += $amount; // Si restó, ahora suma
            }
        }

        // Opcional: Asegurar que el balance no sea negativo, a menos que tu negocio lo permita.
        // $cashBox->current_balance = max(0, $cashBox->current_balance);

        // Guarda los cambios en la CashBox.
        $cashBox->save();
    }
}