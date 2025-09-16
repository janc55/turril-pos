<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function generateTicket($saleId)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($saleId);  // Carga la venta con relaciones

        $pdf = Pdf::loadView('pdf.ticket', compact('sale'));  // Carga la vista con datos
        $pdf->setPaper('a5', 'portrait');  // Opcional: ajusta tamaño para ticket pequeño

        return $pdf->download('ticket-' . $saleId . '.pdf');  // Descarga el PDF
    }

    public function generateReceipt($saleId)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($saleId);

        $pdf = Pdf::loadView('pdf.receipt', compact('sale'));
        $pdf->setPaper('a4', 'portrait');  // Tamaño estándar para recibo

        return $pdf->download('recibo-' . $saleId . '.pdf');  // Descarga el PDF
    }
}
