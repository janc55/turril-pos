<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $modelLabel = 'Venta';

    protected static ?string $navigationLabel = 'Ventas';

    protected static ?string $navigationGroup = 'Ventas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('branch_id')
                    ->label('Sucursal')
                    ->required()
                    ->relationship('branch', 'name'),
                Forms\Components\TextInput::make('total_amount')
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
                Forms\Components\TextInput::make('discount_amount')
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
                Forms\Components\TextInput::make('final_amount')
                    ->label('Monto Final')
                    ->required()
                    ->prefix('Bs.')
                    ->numeric()
                    ->disabled(),
                Forms\Components\Select::make('payment_method')
                    ->label('Método de Pago')
                    ->options([
                        'cash' => 'Efectivo',
                        'credit_card' => 'Tarjeta de Crédito',
                        'debit_card' => 'Tarjeta de Débito',
                        'transfer' => 'Transferencia',
                        'other' => 'Otro',
                    ])
                    ->required(),
                Forms\Components\Radio::make('status')
                    ->label('Estado')
                    ->options([
                        'completed' => 'Completada',
                        'pending' => 'Pendiente',
                        'cancelled' => 'Cancelada',
                    ])
                    ->default('completed')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Sucursal')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Monto Total')
                    ->money('Bs.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_amount')
                    ->label('Descuento')
                    ->money('Bs.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('final_amount')
                    ->label('Monto Final')
                    ->money('Bs.')
                    ->sortable(),
                Tables\Columns\IconColumn::make('payment_method')
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
                Tables\Columns\TextColumn::make('status'),
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
