<?php

namespace App\Filament\Resources\CashMovementResource\Pages;

use App\Filament\Resources\CashMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCashMovement extends CreateRecord
{
    protected static string $resource = CashMovementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
         // Asigna el user_id actual
        $data['user_id'] = Auth::id();

        return $data;
    }
}
