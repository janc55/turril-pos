<?php

namespace App\Filament\Resources\Spatie\Permission\Models\PermissionResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Spatie\Permission\Models\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
