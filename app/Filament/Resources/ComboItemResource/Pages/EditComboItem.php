<?php

namespace App\Filament\Resources\ComboItemResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\ComboItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComboItem extends EditRecord
{
    protected static string $resource = ComboItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
