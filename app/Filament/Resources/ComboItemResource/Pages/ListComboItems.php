<?php

namespace App\Filament\Resources\ComboItemResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ComboItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComboItems extends ListRecords
{
    protected static string $resource = ComboItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
