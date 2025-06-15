<?php

namespace App\Filament\Widgets;

use App\Models\Sale; // Importa tu modelo Sale
use App\Models\CashBox; // Importa tu modelo CashBox
use App\Models\CurrentStock; // Importa tu modelo CurrentStock
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth; // Para acceder al usuario autenticado

class SalesOverview extends BaseWidget
{
    /**
     * Define si este widget puede ser visto por el usuario actual.
     * Si contiene stats para varios roles, será visible si el usuario tiene permiso para ver ALGUN stat dentro.
     * Esta función controla la visibilidad de TODO EL WIDGET.
     * Si no hay ningún stat que mostrar, el widget no aparecerá.
     */
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        $user = Auth::user();
        // El widget será visible para cualquier rol que pueda ver al menos uno de sus stats internos.
        return $user->hasRole('Administrador') ||
               $user->hasRole('Gerente') ||
               $user->hasRole('Cajero') ||
               $user->hasRole('Almacenero'); // Incluido si se añaden stats de stock
    }

    protected function getStats(): array
    {
        $user = Auth::user();
        $userBranchId = $user->branch_id;
        $stats = []; // Array para almacenar los objetos Stat a retornar

        // === 1. Ventas Hoy Stat ===
        // Solo añadir este stat si el usuario tiene un rol relevante
        if ($user->hasRole('Administrador') || $user->hasRole('Gerente') || $user->hasRole('Cajero')) {
            $salesQuery = Sale::query()
                ->whereDate('created_at', today())
                ->where('status', 'completed');

            if (!$user->hasRole('Administrador') && $userBranchId) {
                $salesQuery->where('branch_id', $userBranchId);
            }

            $totalSalesToday = $salesQuery->sum('final_amount');

            $stats[] = Stat::make('Ventas Hoy', 'Bs. ' . number_format($totalSalesToday, 2))
                           ->description('Total de ventas del día')
                           ->descriptionIcon('heroicon-m-arrow-trending-up')
                           ->color('success');
        }

        // === 2. Órdenes Hoy Stat ===
        // Solo añadir este stat si el usuario tiene un rol relevante
        if ($user->hasRole('Administrador') || $user->hasRole('Gerente') || $user->hasRole('Cajero')) {
            // Reutilizamos la misma consulta de ventas
            $ordersQuery = Sale::query()
                ->whereDate('created_at', today())
                ->where('status', 'completed');

            if (!$user->hasRole('Administrador') && $userBranchId) {
                $ordersQuery->where('branch_id', $userBranchId);
            }
            $totalOrdersToday = $ordersQuery->count();

            $stats[] = Stat::make('Órdenes Hoy', $totalOrdersToday)
                           ->description('Número de órdenes (ventas) realizadas hoy')
                           ->descriptionIcon('heroicon-m-shopping-bag')
                           ->color('info');
        }

        // === 3. Saldo de Caja Stat ===
        // Solo añadir este stat si el usuario tiene un rol relevante para la caja
        if (($user->hasRole('Cajero') || $user->hasRole('Gerente')) && $userBranchId) {
            $cashBox = CashBox::where('branch_id', $userBranchId)
                              ->where('status', 'open') // O la lógica para la caja activa del cajero
                              ->first();

            if ($cashBox) {
                $stats[] = Stat::make('Saldo de Caja', 'Bs. ' . number_format($cashBox->current_balance, 2))
                               ->description('Saldo actual de tu caja')
                               ->descriptionIcon('heroicon-m-wallet')
                               ->color('primary');
            } else {
                $stats[] = Stat::make('Saldo de Caja', 'N/A')
                               ->description('No hay caja abierta para tu sucursal')
                               ->descriptionIcon('heroicon-m-exclamation-triangle')
                               ->color('warning');
            }
        } elseif ($user->hasRole('Administrador')) {
            // Para administradores, mostrar un resumen de todas las cajas abiertas
            $totalOpenCashBoxes = CashBox::where('status', 'open')->count();
            $stats[] = Stat::make('Cajas Abiertas Globales', $totalOpenCashBoxes)
                           ->description('Número de cajas abiertas en todas las sucursales')
                           ->descriptionIcon('heroicon-m-check-circle')
                           ->color('success');
        }

        // === 4. Bajo Stock Stat (si lo quieres añadir en este mismo widget) ===
        // Solo añadir este stat si el usuario tiene un rol relevante para el stock
        if ($user->hasRole('Administrador') || $user->hasRole('Gerente') || $user->hasRole('Almacenero')) {
            // Asegúrate de importar App\Models\CurrentStock al inicio del archivo
            // use App\Models\CurrentStock;
            $stockQuery = CurrentStock::query();
            $lowStockThreshold = 10; // Define tu umbral de bajo stock

            if (!$user->hasRole('Administrador') && $userBranchId) {
                $stockQuery->where('branch_id', $userBranchId);
            }

            $lowStockCount = $stockQuery->where('quantity', '<=', $lowStockThreshold)->count();

            $stats[] = Stat::make('Bajo Stock', $lowStockCount)
                           ->description('Elementos por debajo del umbral de ' . $lowStockThreshold)
                           ->descriptionIcon('heroicon-m-arrow-down-tray')
                           ->color('warning');
        }

        return $stats;
    }
}