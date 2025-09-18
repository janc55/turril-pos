<?php

namespace App\Filament\Resources\SaleItemResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SaleItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSaleItems extends ListRecords
{
    protected static string $resource = SaleItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
