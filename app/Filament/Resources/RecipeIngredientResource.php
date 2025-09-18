<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\RecipeIngredientResource\Pages\ListRecipeIngredients;
use App\Filament\Resources\RecipeIngredientResource\Pages\CreateRecipeIngredient;
use App\Filament\Resources\RecipeIngredientResource\Pages\EditRecipeIngredient;
use App\Filament\Resources\RecipeIngredientResource\Pages;
use App\Filament\Resources\RecipeIngredientResource\RelationManagers;
use App\Models\RecipeIngredient;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecipeIngredientResource extends Resource
{
    protected static ?string $model = RecipeIngredient::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationParentItem = 'Recetas';

    protected static string | \UnitEnum | null $navigationGroup = 'Administración';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('recipe_id')
                    ->required()
                    ->numeric(),
                TextInput::make('ingredient_id')
                    ->required()
                    ->numeric(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('recipe.product.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ingredient.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecipeIngredients::route('/'),
            'create' => CreateRecipeIngredient::route('/create'),
            'edit' => EditRecipeIngredient::route('/{record}/edit'),
        ];
    }
}
