<?php

namespace App\Filament\Resources\CashMovementResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\CashMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCashMovements extends ListRecords
{
    protected static string $resource = CashMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
