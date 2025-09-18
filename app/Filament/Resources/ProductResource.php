<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ProductResource\Pages\ListProducts;
use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $modelLabel = 'Producto';

    protected static ?string $navigationLabel = 'Productos';

    protected static string | \UnitEnum | null $navigationGroup = 'Inventario';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(50),
                TextInput::make('price')
                    ->label('Precio')
                    ->required()
                    ->numeric()
                    ->prefix('Bs.'),
                TextInput::make('cost')
                    ->label('Costo')
                    ->numeric()
                    ->prefix('$'),
                Select::make('type')
                    ->label('Tipo')
                    ->live()
                    ->options([
                        'sandwich' => 'Sandwich',
                        'drink' => 'Bebida',
                        'combo' => 'Combo',
                    ])
                    ->required(),
                FileUpload::make('image')
                    ->label('Imagen')
                    ->directory('products')
                    ->disk('public')
                    ->image(),
                Toggle::make('active')
                    ->label('Activo')
                    ->required(),
                Toggle::make('stock_management')
                    ->label('Gestión de Stock')
                    ->required(),
                Toggle::make('is_combo')
                    ->label('Es Combo')
                    ->required(),
                Repeater::make('comboItems')
                    ->relationship('comboItems') // Esta es la clave, así se vinculan los items al combo automáticamente
                    ->label('Productos del Combo')
                    ->schema([
                        Select::make('product_id')
                            ->label('Producto')
                            ->options(Product::where('type', '!=', 'combo')->pluck('name', 'id'))
                            ->required(),
                        TextInput::make('quantity')->numeric()->default(1)->minValue(1)->required(),
                    ])
                    ->visible(fn ($get) => $get('type') === 'combo')
                    ->addActionLabel('Agregar producto')
                    ->columns(2),
                            ]);
                    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('BOB')
                    ->sortable(),
                TextColumn::make('cost')
                    ->label('Costo')
                    ->money('BOB')
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->sortable(),
                ImageColumn::make('image')
                    ->label('Imagen')
                    ->disk('public')
                    ->circular(),
                IconColumn::make('active')
                    ->label('Activo')
                    ->boolean(),
                IconColumn::make('stock_management')
                    ->label('Gestión de Stock')
                    ->boolean(),
                IconColumn::make('is_combo')
                    ->label('Es Combo')
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
