<?php

namespace App\Filament\Resources\SafeEntryResource\Pages;

use App\Filament\Resources\SafeEntryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSafeEntry extends EditRecord
{
    protected static string $resource = SafeEntryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
