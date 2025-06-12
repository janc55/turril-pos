<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComboItemResource\Pages;
use App\Filament\Resources\ComboItemResource\RelationManagers;
use App\Models\ComboItem;
use App\Models\Product;
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

    protected static ?string $modelLabel = 'Combo';

    protected static ?string $navigationLabel = 'Combos';

    protected static ?string $navigationParentItem = 'Productos';

    protected static ?string $navigationGroup = 'Inventario';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('combo_product_id')
                    ->label('Combo')
                    ->options(
                        Product::where('type', '=', 'combo') // Filtra para excluir productos que son combos
                            ->pluck('name', 'id')
                    )
                    ->required(),
                Forms\Components\Select::make('product_id')
                    ->options(
                        Product::where('type', '!=', 'combo') // Filtra para excluir productos que son combos
                            ->pluck('name', 'id')
                    )
                    ->required()
                    ->label('Producto'),
                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad')    
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('min_choices')
                    ->label('Mínimo de opciones')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('max_choices')
                    ->label('Máximo de opciones')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\Toggle::make('is_customizable')
                    ->label('Personalizable')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('comboProduct.name')
                    ->label('Combo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Producto')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_customizable')
                    ->label('Personalizable')
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
