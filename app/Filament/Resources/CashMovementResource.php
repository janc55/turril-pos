<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CashMovementResource\Pages\ListCashMovements;
use App\Filament\Resources\CashMovementResource\Pages\CreateCashMovement;
use App\Filament\Resources\CashMovementResource\Pages\EditCashMovement;
use App\Filament\Resources\CashMovementResource\Pages;
use App\Filament\Resources\CashMovementResource\RelationManagers;
use App\Models\CashMovement;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CashMovementResource extends Resource
{
    protected static ?string $model = CashMovement::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrows-up-down';

    protected static ?string $modelLabel = 'Movimientos de Caja';

    protected static ?string $navigationLabel = 'Movimientos de Caja';

    protected static string | \UnitEnum | null $navigationGroup = 'Caja';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cash_box_id')
                    ->label('Caja')
                    ->required()
                    ->relationship('cashBox', 'name'),
                Select::make('type')
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
                TextInput::make('amount')
                    ->label('Monto')
                    ->required()
                    ->prefix('Bs.')
                    ->numeric(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                TextInput::make('related_sale_id')
                    ->numeric(),
                TextInput::make('related_purchase_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cashBox.name')
                    ->label('Caja')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type')
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
                TextColumn::make('amount')
                    ->label('Monto')
                    ->money('Bs.')
                    ->sortable(),
                IconColumn::make('related_sale_id')
                    ->label('Venta relacionada')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->color('success')
                    ->sortable(),
                IconColumn::make('related_purchase_id')
                    ->label('Compra relacionada')
                    ->icon(fn (string $state): string => match ($state) {
                        '1' => 'heroicon-o-arrow-down-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'danger',
                        default => 'gray',
                    })
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
            'index' => ListCashMovements::route('/'),
            'create' => CreateCashMovement::route('/create'),
            'edit' => EditCashMovement::route('/{record}/edit'),
        ];
    }
}
