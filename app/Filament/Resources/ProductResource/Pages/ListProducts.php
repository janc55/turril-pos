<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Producto')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
