<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SaleItemResource\Pages\ListSaleItems;
use App\Filament\Resources\SaleItemResource\Pages\CreateSaleItem;
use App\Filament\Resources\SaleItemResource\Pages\EditSaleItem;
use App\Filament\Resources\SaleItemResource\Pages;
use App\Filament\Resources\SaleItemResource\RelationManagers;
use App\Models\SaleItem;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaleItemResource extends Resource
{
    protected static ?string $model = SaleItem::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $modelLabel = 'Venta de Artículo';

    protected static ?string $navigationLabel = 'Ventas de Artículos';

    protected static string | \UnitEnum | null $navigationGroup = 'Ventas';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sale_id')
                    ->label('Venta')
                    ->required()
                    ->relationship('sale', 'id'),
                Select::make('product_id')
                    ->label('Producto')
                    ->required()
                    ->relationship('product', 'name'),
                TextInput::make('quantity')
                    ->label('Cantidad')    
                    ->required()
                    ->numeric(),
                TextInput::make('unit_price')
                    ->label('Precio Unitario')
                    ->required()
                    ->prefix('Bs.')
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $quantity = floatval($get('quantity'));
                        $unitPrice = floatval($state);
                        $set('subtotal', max($quantity * $unitPrice, 0));
                    }),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.00)
                    ->disabled(),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0.00)
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $subtotal = floatval($get('subtotal'));
                        $discount = floatval($state);
                        $set('total', max($subtotal - $discount, 0));
                    }),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.00)
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sale_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Producto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('Precio Unitario')
                    ->money('Bs.')
                    ->sortable(),
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('Bs.')
                    ->sortable(),
                TextColumn::make('discount')
                    ->label('Descuento')
                    ->money('Bs.')
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('Bs.')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')->money('BOB')),
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
            'index' => ListSaleItems::route('/'),
            'create' => CreateSaleItem::route('/create'),
            'edit' => EditSaleItem::route('/{record}/edit'),
        ];
    }
}
