<?php

namespace App\Filament\Resources\CashMovementResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CashMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashMovement extends EditRecord
{
    protected static string $resource = CashMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
