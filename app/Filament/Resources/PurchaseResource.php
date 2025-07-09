<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Models\Ingredient;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $modelLabel = 'Compra';

    protected static ?string $navigationLabel = 'Compras';

    protected static ?string $navigationGroup = 'Inventario';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('branch_id')
                    ->label('Sucursal')
                    ->relationship('branch', 'name')
                    ->required(),
                Forms\Components\Select::make('supplier_id')
                    ->label('Proveedor')
                    ->relationship('supplier', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email(),
                    ])
                    ->required(),
                Forms\Components\ToggleButtons::make('payment_status')
                    ->label('Estado de Pago')
                    ->options([
                        'paid' => 'Pagado',
                        'pending' => 'Pendiente',
                        'cancelled' => 'Cancelado',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\TextInput::make('invoice_number')
                    ->maxLength(100),
                Forms\Components\DatePicker::make('purchase_date')
                    ->label('Fecha de Compra')
                    ->default(now())
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Repeater::make('purchaseItems')
                    ->label('Items de Compra')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('ingredient_id')
                            ->label('Ingrediente')
                            ->relationship('ingredient', 'name')
                            ->live()
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Cantidad')
                            ->prefix(fn(Get $get) => Ingredient::find($get('ingredient_id'))?->unit ?? 'Unidades')
                            ->required()
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state, Forms\Get $get) {
                                $quantity = $get('quantity');
                                $unitCost = $get('unit_cost');
                                if ($quantity && $unitCost) {
                                    $set('subtotal', $quantity * $unitCost);
                                }
                            }),
                        Forms\Components\TextInput::make('unit_cost')
                            ->label('Costo Unitario')
                            ->required()
                            ->numeric()
                            ->prefix('Bs.')
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state, Forms\Get $get) {
                                $quantity = $get('quantity');
                                $unitCost = $get('unit_cost');
                                if ($quantity && $unitCost) {
                                    $set('subtotal', $quantity * $unitCost);
                                }
                            }),
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->required()
                            ->numeric()
                            ->prefix('Bs.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('branch.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')->money('BOB')),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge(),
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchase_date')
                    ->date()
                    ->sortable(),
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}
