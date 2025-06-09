<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComboItemResource\Pages;
use App\Filament\Resources\ComboItemResource\RelationManagers;
use App\Models\ComboItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComboItemResource extends Resource
{
    protected static ?string $model = ComboItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('combo_product_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('product_id')
                    ->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('min_choices')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('max_choices')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\Toggle::make('is_customizable')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('combo_product_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_choices')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_choices')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_customizable')
                    ->boolean(),
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
            'index' => Pages\ListComboItems::route('/'),
            'create' => Pages\CreateComboItem::route('/create'),
            'edit' => Pages\EditComboItem::route('/{record}/edit'),
        ];
    }
}
