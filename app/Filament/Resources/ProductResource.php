<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $modelLabel = 'Producto';

    protected static ?string $navigationLabel = 'Productos';

    protected static ?string $navigationGroup = 'Inventario';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(50),
                Forms\Components\TextInput::make('price')
                    ->label('Precio')
                    ->required()
                    ->numeric()
                    ->prefix('Bs.'),
                Forms\Components\TextInput::make('cost')
                    ->label('Costo')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->live()
                    ->options([
                        'sandwich' => 'Sandwich',
                        'drink' => 'Bebida',
                        'combo' => 'Combo',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->label('Imagen')
                    ->directory('products')
                    ->image(),
                Forms\Components\Toggle::make('active')
                    ->label('Activo')
                    ->required(),
                Forms\Components\Toggle::make('stock_management')
                    ->label('Gestión de Stock')
                    ->required(),
                Forms\Components\Toggle::make('is_combo')
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('BOB')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Costo')
                    ->money('BOB')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagen'),
                Tables\Columns\IconColumn::make('active')
                    ->label('Activo')
                    ->boolean(),
                Tables\Columns\IconColumn::make('stock_management')
                    ->label('Gestión de Stock')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_combo')
                    ->label('Es Combo')
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
