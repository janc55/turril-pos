<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CashMovementResource\Pages;
use App\Filament\Resources\CashMovementResource\RelationManagers;
use App\Models\CashMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CashMovementResource extends Resource
{
    protected static ?string $model = CashMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Movimientos de Caja';

    protected static ?string $navigationLabel = 'Movimientos de Caja';

    protected static ?string $navigationGroup = 'Caja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cash_box_id')
                    ->label('Caja')
                    ->required()
                    ->relationship('cashBox', 'name'),
                Forms\Components\Select::make('type')
                    ->label('Tipo de movimiento')
                    ->options([
                        'deposit' => 'Depósito',
                        'withdrawal' => 'Retiro',
                        'sale_income' => 'Ingreso por venta',
                        'purchase_payment' => 'Pago de compra',
                        'other_income' => 'Otro ingreso',
                        'other_expense' => 'Otro gasto',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Monto')
                    ->required()
                    ->prefix('Bs.')
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('related_sale_id')
                    ->numeric(),
                Forms\Components\TextInput::make('related_purchase_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cashBox.name')
                    ->label('Caja')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo de movimiento')
                    ->badge(function ($state) {
                        return match ($state) {
                            'deposit' => 'Depósito',
                            'withdrawal' => 'Retiro',
                            'sale_income' => 'Ingreso por venta',
                            'purchase_payment' => 'Pago de compra',
                            'other_income' => 'Otro ingreso',
                            'other_expense' => 'Otro gasto',
                        };
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->money('Bs.')
                    ->sortable(),
                Tables\Columns\IconColumn::make('related_sale_id')
                    ->label('Venta relacionada')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->color('success')
                    ->sortable(),
                Tables\Columns\IconColumn::make('related_purchase_id')
                    ->label('Compra relacionada')
                    ->icon(fn (string $state): string => match ($state) {
                        '1' => 'heroicon-o-arrow-down-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'danger',
                        default => 'gray',
                    })
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
            'index' => Pages\ListCashMovements::route('/'),
            'create' => Pages\CreateCashMovement::route('/create'),
            'edit' => Pages\EditCashMovement::route('/{record}/edit'),
        ];
    }
}
