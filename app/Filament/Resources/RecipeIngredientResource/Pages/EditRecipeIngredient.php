<?php

namespace App\Filament\Resources\RecipeIngredientResource\Pages;

use App\Filament\Resources\RecipeIngredientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecipeIngredient extends EditRecord
{
    protected static string $resource = RecipeIngredientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
