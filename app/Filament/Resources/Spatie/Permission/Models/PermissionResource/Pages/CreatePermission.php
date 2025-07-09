<?php

namespace App\Filament\Resources\Spatie\Permission\Models\PermissionResource\Pages;

use App\Filament\Resources\Spatie\Permission\Models\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
}
