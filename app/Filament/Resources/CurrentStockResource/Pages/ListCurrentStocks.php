<?php

namespace App\Filament\Resources\CurrentStockResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\CurrentStockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCurrentStocks extends ListRecords
{
    protected static string $resource = CurrentStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Agregar Stock')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
