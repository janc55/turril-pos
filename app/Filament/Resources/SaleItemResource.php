<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleItemResource\Pages;
use App\Filament\Resources\SaleItemResource\RelationManagers;
use App\Models\SaleItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaleItemResource extends Resource
{
    protected static ?string $model = SaleItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Venta de Artículo';

    protected static ?string $navigationLabel = 'Ventas de Artículos';

    protected static ?string $navigationGroup = 'Ventas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sale_id')
                    ->label('Venta')
                    ->required()
                    ->relationship('sale', 'id'),
                Forms\Components\Select::make('product_id')
                    ->label('Producto')
                    ->required()
                    ->relationship('product', 'name'),
                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad')    
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('unit_price')
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
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.00)
                    ->disabled(),
                Forms\Components\TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0.00)
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $subtotal = floatval($get('subtotal'));
                        $discount = floatval($state);
                        $set('total', max($subtotal - $discount, 0));
                    }),
                Forms\Components\TextInput::make('total')
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
                Tables\Columns\TextColumn::make('sale_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Producto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Precio Unitario')
                    ->money('Bs.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('Bs.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->label('Descuento')
                    ->money('Bs.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('Bs.')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')->money('BOB')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListSaleItems::route('/'),
            'create' => Pages\CreateSaleItem::route('/create'),
            'edit' => Pages\EditSaleItem::route('/{record}/edit'),
        ];
    }
}
