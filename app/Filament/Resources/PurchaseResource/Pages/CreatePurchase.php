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

        return $data;
    }

    protected function afterCreate(): void
    {
        $purchase = $this->record;

        $total = 0;
        if ($purchase->purchaseItems && is_iterable($purchase->purchaseItems)) {
            foreach ($purchase->purchaseItems as $item) {
                $total += (float) ($item->subtotal ?? 0);
            }
        }
        $purchase->total_amount = round($total, 2);
        $purchase->save();
    }

}
