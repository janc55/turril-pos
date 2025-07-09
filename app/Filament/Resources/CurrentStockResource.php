<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurrentStockResource\Pages;
use App\Filament\Resources\CurrentStockResource\RelationManagers;
use App\Models\CurrentStock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurrentStockResource extends Resource
{
    protected static ?string $model = CurrentStock::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $modelLabel = 'Stock Actual';

    protected static ?string $navigationLabel = 'Stocks Actuales';

    protected static ?string $navigationGroup = 'Inventario';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('branch_id')
                    ->label('Sucursal')
                    ->required()
                    ->relationship('branch', 'name'),
                Forms\Components\Select::make('product_id')
                    ->label('Producto')
                    ->relationship('product', 'name')
                    ->unique(ignoreRecord: true, column: 'product_id'),
                Forms\Components\Select::make('ingredient_id')
                    ->label('Ingrediente')
                    ->relationship('ingredient', 'name')
                    ->unique(ignoreRecord: true, column: 'ingredient_id'),
                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Sucursal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Producto')    
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingredient.name')
                    ->label('Ingrediente')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCurrentStocks::route('/'),
            'create' => Pages\CreateCurrentStock::route('/create'),
            'edit' => Pages\EditCurrentStock::route('/{record}/edit'),
        ];
    }
}
