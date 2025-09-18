<?php

namespace App\Filament\Resources\RecipeResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\RecipeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecipes extends ListRecords
{
    protected static string $resource = RecipeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva Receta')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
