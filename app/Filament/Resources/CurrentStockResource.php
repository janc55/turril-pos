<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CurrentStockResource\Pages\ListCurrentStocks;
use App\Filament\Resources\CurrentStockResource\Pages\CreateCurrentStock;
use App\Filament\Resources\CurrentStockResource\Pages\EditCurrentStock;
use App\Filament\Resources\CurrentStockResource\Pages;
use App\Filament\Resources\CurrentStockResource\RelationManagers;
use App\Models\CurrentStock;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurrentStockResource extends Resource
{
    protected static ?string $model = CurrentStock::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $modelLabel = 'Stock Actual';

    protected static ?string $navigationLabel = 'Stocks Actuales';

    protected static string | \UnitEnum | null $navigationGroup = 'Inventario';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->label('Sucursal')
                    ->required()
                    ->relationship('branch', 'name'),
                Select::make('product_id')
                    ->label('Producto')
                    ->relationship('product', 'name')
                    ->unique(ignoreRecord: true, column: 'product_id'),
                Select::make('ingredient_id')
                    ->label('Ingrediente')
                    ->relationship('ingredient', 'name')
                    ->unique(ignoreRecord: true, column: 'ingredient_id'),
                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')
                    ->label('Sucursal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Producto')    
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ingredient.name')
                    ->label('Ingrediente')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),
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
            'index' => ListCurrentStocks::route('/'),
            'create' => CreateCurrentStock::route('/create'),
            'edit' => EditCurrentStock::route('/{record}/edit'),
        ];
    }
}
