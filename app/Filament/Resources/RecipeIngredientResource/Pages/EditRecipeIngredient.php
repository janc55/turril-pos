<?php

namespace App\Filament\Resources\RecipeIngredientResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\RecipeIngredientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecipeIngredient extends EditRecord
{
    protected static string $resource = RecipeIngredientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
