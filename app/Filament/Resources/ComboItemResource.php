<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ComboItemResource\Pages\ListComboItems;
use App\Filament\Resources\ComboItemResource\Pages\CreateComboItem;
use App\Filament\Resources\ComboItemResource\Pages\EditComboItem;
use App\Filament\Resources\ComboItemResource\Pages;
use App\Filament\Resources\ComboItemResource\RelationManagers;
use App\Models\ComboItem;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComboItemResource extends Resource
{
    protected static ?string $model = ComboItem::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Combo';

    protected static ?string $navigationLabel = 'Combos';

    protected static ?string $navigationParentItem = 'Productos';

    protected static string | \UnitEnum | null $navigationGroup = 'Inventario';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('combo_product_id')
                    ->label('Combo')
                    ->options(
                        Product::where('type', '=', 'combo') // Filtra para excluir productos que son combos
                            ->pluck('name', 'id')
                    )
                    ->required(),
                Select::make('product_id')
                    ->options(
                        Product::where('type', '!=', 'combo') // Filtra para excluir productos que son combos
                            ->pluck('name', 'id')
                    )
                    ->required()
                    ->label('Producto'),
                TextInput::make('quantity')
                    ->label('Cantidad')    
                    ->required()
                    ->numeric(),
                TextInput::make('min_choices')
                    ->label('Mínimo de opciones')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('max_choices')
                    ->label('Máximo de opciones')
                    ->required()
                    ->numeric()
                    ->default(1),
                Toggle::make('is_customizable')
                    ->label('Personalizable')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('comboProduct.name')
                    ->label('Combo')
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Producto')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->sortable(),
                IconColumn::make('is_customizable')
                    ->label('Personalizable')
                    ->boolean(),
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
            'index' => ListComboItems::route('/'),
            'create' => CreateComboItem::route('/create'),
            'edit' => EditComboItem::route('/{record}/edit'),
        ];
    }
}
