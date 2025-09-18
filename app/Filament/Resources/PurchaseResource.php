<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\PurchaseResource\Pages\ListPurchases;
use App\Filament\Resources\PurchaseResource\Pages\CreatePurchase;
use App\Filament\Resources\PurchaseResource\Pages\EditPurchase;
use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Models\Ingredient;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $modelLabel = 'Compra';

    protected static ?string $navigationLabel = 'Compras';

    protected static string | \UnitEnum | null $navigationGroup = 'Inventario';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->label('Sucursal')
                    ->relationship('branch', 'name')
                    ->required(),
                Select::make('supplier_id')
                    ->label('Proveedor')
                    ->relationship('supplier', 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->email(),
                    ])
                    ->required(),
                ToggleButtons::make('payment_status')
                    ->label('Estado de Pago')
                    ->options([
                        'paid' => 'Pagado',
                        'pending' => 'Pendiente',
                        'cancelled' => 'Cancelado',
                    ])
                    ->default('pending')
                    ->required(),
                TextInput::make('invoice_number')
                    ->maxLength(100),
                DatePicker::make('purchase_date')
                    ->label('Fecha de Compra')
                    ->default(now())
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Repeater::make('purchaseItems')
                    ->label('Items de Compra')
                    ->relationship()
                    ->schema([
                        Select::make('ingredient_id')
                            ->label('Ingrediente')
                            ->relationship('ingredient', 'name')
                            ->live()
                            ->required(),
                        TextInput::make('quantity')
                            ->label('Cantidad')
                            ->prefix(fn(Get $get) => Ingredient::find($get('ingredient_id'))?->unit ?? 'Unidades')
                            ->required()
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                $quantity = $get('quantity');
                                $unitCost = $get('unit_cost');
                                if ($quantity && $unitCost) {
                                    $set('subtotal', $quantity * $unitCost);
                                }
                            }),
                        TextInput::make('unit_cost')
                            ->label('Costo Unitario')
                            ->required()
                            ->numeric()
                            ->prefix('Bs.')
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                $quantity = $get('quantity');
                                $unitCost = $get('unit_cost');
                                if ($quantity && $unitCost) {
                                    $set('subtotal', $quantity * $unitCost);
                                }
                            }),
                        TextInput::make('subtotal')
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
                TextColumn::make('branch.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('supplier.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')->money('BOB')),
                TextColumn::make('payment_status')
                    ->badge(),
                TextColumn::make('invoice_number')
                    ->searchable(),
                TextColumn::make('purchase_date')
                    ->date()
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
            'index' => ListPurchases::route('/'),
            'create' => CreatePurchase::route('/create'),
            'edit' => EditPurchase::route('/{record}/edit'),
        ];
    }
}
