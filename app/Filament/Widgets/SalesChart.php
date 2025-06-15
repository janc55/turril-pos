<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Sale; // Importa tu modelo Sale
use Carbon\Carbon; // Para trabajar con fechas
use Illuminate\Support\Facades\Auth;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Resumen de Ventas (Últimos 6 Meses)';
    protected static string $color = 'info';
    protected static ?int $sort = 2;

    /**
     * Solo visible para administradores y gerentes.
     */
    public static function canView(): bool
    {
        $user = Auth::user();
        return $user->hasRole('Administrador') || $user->hasRole('Gerente');
    }

    protected function getType(): string
    {
        return 'bar'; // Puedes usar 'line', 'bar', 'pie', 'doughnut', 'polarArea', 'radar'
    }

    protected function getData(): array
    {
        $user = Auth::user();
        $months = [];
        $salesData = [];

        // Generar los últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('F'); // Nombre del mes traducido
            $months[] = ucfirst($monthName); // Capitaliza la primera letra

            // Consulta para ventas del mes
            $query = Sale::query()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('status', 'completed'); // Solo ventas completadas

            // Aplicar filtro por sucursal si el usuario no es administrador y tiene branch_id
            if (!$user->hasRole('Administrador') && $user->branch_id) {
                $query->where('branch_id', $user->branch_id);
            }

            $salesData[] = $query->sum('final_amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ventas',
                    'data' => $salesData,
                    'backgroundColor' => '#3B82F6', // Color de las barras (azul Filament)
                    'borderColor' => '#3B82F6',
                ],
            ],
            'labels' => $months,
        ];
    }
}