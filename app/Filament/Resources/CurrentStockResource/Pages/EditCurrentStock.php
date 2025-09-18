<?php

namespace App\Filament\Resources\CurrentStockResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CurrentStockResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCurrentStock extends EditRecord
{
    protected static string $resource = CurrentStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
