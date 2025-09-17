<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingsResource\Pages;
use App\Filament\Resources\SettingsResource\RelationManagers;
use App\Models\Settings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;

class SettingsResource extends Resource
{
    protected static ?string $model = Settings::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('tenant_id')
                    ->default(null), // Nullable para configuraciones globales
                Forms\Components\TextInput::make('key')
                    ->label('Clave')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('value')
                    ->label('Valor')
                    ->maxLength(255)
                    ->visible(fn ($record) => $record ? in_array($record->key, ['app_name', 'currency_symbol']) : true),
                Forms\Components\FileUpload::make('value')
                    ->label('Logo')
                    ->image()
                    ->directory('logos')
                    ->disk('public')
                    ->preserveFilenames()
                    ->maxSize(2048)
                    ->visible(fn ($record) => $record && $record->key === 'logo_path'),
                Forms\Components\Select::make('value')
                    ->label('Moneda')
                    ->options([
                        'BOB' => 'Boliviano (Bs.)',
                        'USD' => 'Dólar Estadounidense ($)',
                        'EUR' => 'Euro (€)',
                    ])
                    ->required()
                    ->visible(fn ($record) => $record && $record->key === 'currency_code')
                    ->reactive()
                    ->afterStateUpdated(function ($state, $record) {
                        if ($record && $record->key === 'currency_code') {
                            $symbols = ['BOB' => 'Bs.', 'USD' => '$', 'EUR' => '€'];
                            Settings::updateOrCreate(
                                ['tenant_id' => null, 'key' => 'currency_symbol'],
                                ['value' => $symbols[$state] ?? 'Bs.']
                            );
                            Cache::forget('settings_global');
                        }
                    }),
                Forms\Components\Select::make('value')
                    ->label('Idioma')
                    ->options([
                        'es' => 'Español',
                        'en' => 'Inglés',
                    ])
                    ->required()
                    ->visible(fn ($record) => $record && $record->key === 'locale'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->label('Clave'),
                Tables\Columns\TextColumn::make('value')->label('Valor'),
                Tables\Columns\ImageColumn::make('value')
                    ->label('Logo')
                    ->visible(fn ($record) => $record !== null && $record->key === 'logo_path'),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSettings::route('/create'),
            'edit' => Pages\EditSettings::route('/{record}/edit'),
        ];
    }
}
