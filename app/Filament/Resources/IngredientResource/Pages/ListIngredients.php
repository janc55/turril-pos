<?php

namespace App\Filament\Resources\IngredientResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\IngredientResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIngredients extends ListRecords
{
    protected static string $resource = IngredientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Agregar Ingrediente')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
