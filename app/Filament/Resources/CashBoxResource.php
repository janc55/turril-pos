<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CashBoxResource\Pages\ListCashBoxes;
use App\Filament\Resources\CashBoxResource\Pages\CreateCashBox;
use App\Filament\Resources\CashBoxResource\Pages\EditCashBox;
use App\Filament\Resources\CashBoxResource\Pages;
use App\Filament\Resources\CashBoxResource\RelationManagers;
use App\Models\Branch;
use App\Models\CashBox;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CashBoxResource extends Resource
{
    protected static ?string $model = CashBox::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $modelLabel = 'Caja';

    protected static ?string $navigationLabel = 'Cajas';

    protected static string | \UnitEnum | null $navigationGroup = 'Caja';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->label('Sucursal')
                    ->required()
                    ->relationship('branch', 'name')
                    ->live()
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('name', Branch::find($state)?->name)),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('initial_balance')
                    ->required()
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(fn (Set $set, ?float $state) => $set('current_balance', $state ?? 0.00))
                    ->default(0.00),
                TextInput::make('current_balance')
                    ->required()
                    ->numeric(),
                Toggle::make('status')
                    ->label('Estado')
                    ->onColor('success')
                    ->offColor('danger')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('initial_balance')
                    ->label('Saldo Inicial')
                    ->money('Bs.')
                    ->sortable(),
                TextColumn::make('current_balance')
                    ->label('Saldo Actual')
                    ->money('Bs.')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
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
            'index' => ListCashBoxes::route('/'),
            'create' => CreateCashBox::route('/create'),
            'edit' => EditCashBox::route('/{record}/edit'),
        ];
    }
}
