<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SaleResource\Pages\ListSales;
use App\Filament\Resources\SaleResource\Pages\CreateSale;
use App\Filament\Resources\SaleResource\Pages\EditSale;
use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Sale;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $modelLabel = 'Venta';

    protected static ?string $navigationLabel = 'Ventas';

    protected static string | \UnitEnum | null $navigationGroup = 'Ventas';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->label('Sucursal')
                    ->required()
                    ->relationship('branch', 'name'),
                TextInput::make('total_amount')
                    ->label('Monto Total')
                    ->required()
                    ->prefix('Bs.')
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                    // $state es el monto total ingresado
                    $discount = floatval($get('discount_amount'));
                    $total = floatval($state);
                    $set('final_amount', max($total - $discount, 0));
                }),
                TextInput::make('discount_amount')
                    ->label('Descuento')
                    ->required()
                    ->prefix('Bs.')
                    ->numeric()
                    ->live()
                    ->default(0.00)
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $total = floatval($get('total_amount'));
                        $discount = floatval($state);
                        $set('final_amount', max($total - $discount, 0));
                    }),
                TextInput::make('final_amount')
                    ->label('Monto Final')
                    ->required()
                    ->prefix('Bs.')
                    ->numeric()
                    ->disabled(),
                Select::make('payment_method')
                    ->label('Método de Pago')
                    ->options([
                        'cash' => 'Efectivo',
                        'credit_card' => 'Tarjeta de Crédito',
                        'debit_card' => 'Tarjeta de Débito',
                        'transfer' => 'Transferencia',
                        'other' => 'Otro',
                    ])
                    ->required(),
                Radio::make('status')
                    ->label('Estado')
                    ->options([
                        'completed' => 'Completada',
                        'pending' => 'Pendiente',
                        'cancelled' => 'Cancelada',
                    ])
                    ->default('completed')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')
                    ->label('Sucursal')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Monto Total')
                    ->money('Bs.')
                    ->sortable(),
                TextColumn::make('discount_amount')
                    ->label('Descuento')
                    ->money('Bs.')
                    ->sortable(),
                TextColumn::make('final_amount')
                    ->label('Monto Final')
                    ->money('Bs.')
                    ->sortable(),
                IconColumn::make('payment_method')
                    ->label('Método de Pago')
                    ->icon(fn (string $state): string => match ($state) {
                        'Efectivo' => 'heroicon-o-banknotes',
                        'Tarjeta' => 'heroicon-o-credit-card',
                        'QR' => 'heroicon-o-qr-code',
                        'transfer' => 'heroicon-o-arrow-right-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Efectivo' => 'success',
                        'Tarjeta' => 'primary',
                        'QR' => 'warning',
                        'transfer' => 'info',
                    })
                    ->searchable(),
                TextColumn::make('status'),
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
            'index' => ListSales::route('/'),
            'create' => CreateSale::route('/create'),
            'edit' => EditSale::route('/{record}/edit'),
        ];
    }
}
