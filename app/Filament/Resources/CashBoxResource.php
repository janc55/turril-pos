<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CashBoxResource\Pages;
use App\Filament\Resources\CashBoxResource\RelationManagers;
use App\Models\Branch;
use App\Models\CashBox;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CashBoxResource extends Resource
{
    protected static ?string $model = CashBox::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $modelLabel = 'Caja';

    protected static ?string $navigationLabel = 'Cajas';

    protected static ?string $navigationGroup = 'Caja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('branch_id')
                    ->label('Sucursal')
                    ->required()
                    ->relationship('branch', 'name')
                    ->live()
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('name', Branch::find($state)?->name)),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('initial_balance')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('current_balance')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\Toggle::make('status')
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
                Tables\Columns\TextColumn::make('branch.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('initial_balance')
                    ->label('Saldo Inicial')
                    ->money('Bs.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_balance')
                    ->label('Saldo Actual')
                    ->money('Bs.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
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
            'index' => Pages\ListCashBoxes::route('/'),
            'create' => Pages\CreateCashBox::route('/create'),
            'edit' => Pages\EditCashBox::route('/{record}/edit'),
        ];
    }
}
