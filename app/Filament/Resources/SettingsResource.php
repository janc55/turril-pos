<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SettingsResource\Pages\ListSettings;
use App\Filament\Resources\SettingsResource\Pages\CreateSettings;
use App\Filament\Resources\SettingsResource\Pages\EditSettings;
use App\Filament\Resources\SettingsResource\Pages;
use App\Filament\Resources\SettingsResource\RelationManagers;
use App\Models\Settings;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;

class SettingsResource extends Resource
{
    protected static ?string $model = Settings::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('tenant_id')
                    ->default(null), // Nullable para configuraciones globales
                TextInput::make('key')
                    ->label('Clave')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabledOn('edit'),
                TextInput::make('value')
                    ->label('Valor')
                    ->maxLength(255)
                    ->visible(fn ($record) => $record ? in_array($record->key, ['app_name', 'currency_symbol']) : true),
                FileUpload::make('value')
                    ->label('Logo')
                    ->image()
                    ->directory('logos')
                    ->disk('public')
                    ->preserveFilenames()
                    ->maxSize(2048)
                    ->visible(fn ($record) => $record && $record->key === 'logo_path'),
                Select::make('value')
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
                Select::make('value')
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
                TextColumn::make('key')->label('Clave'),
                TextColumn::make('value')->label('Valor'),
                ImageColumn::make('value')
                    ->label('Logo')
                    ->visible(fn ($record) => $record !== null && $record->key === 'logo_path'),
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
            'index' => ListSettings::route('/'),
            'create' => CreateSettings::route('/create'),
            'edit' => EditSettings::route('/{record}/edit'),
        ];
    }
}
