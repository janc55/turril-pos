<?php

namespace App\Filament\Resources\RecipeIngredientResource\Pages;

use App\Filament\Resources\RecipeIngredientResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecipeIngredients extends ListRecords
{
    protected static string $resource = RecipeIngredientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nuevo Ingrediente de Receta')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
