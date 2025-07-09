<?php

namespace App\Filament\Resources\PurchaseResource\Pages;

use App\Filament\Resources\PurchaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
         // Asigna el user_id actual
        $data['user_id'] = Auth::id();

        $data['total_amount'] = 0;

        return $data;
    }

    protected function afterCreate(): void
    {
         $purchase = $this->record->fresh(); // Asegúrate de tener los items cargados
    
        $total = $purchase->purchaseItems->sum('subtotal');
        
        $purchase->update([
            'total_amount' => round($total, 2)
        ]);
    }

}
