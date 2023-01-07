<?php

namespace App\Filament\Resources\SafeEntryCategoryResource\Pages;

use App\Filament\Resources\SafeEntryCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSafeEntryCategory extends EditRecord
{
    protected static string $resource = SafeEntryCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
