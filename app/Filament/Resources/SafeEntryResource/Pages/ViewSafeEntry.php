<?php

namespace App\Filament\Resources\SafeEntryResource\Pages;

use App\Filament\Resources\SafeEntryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSafeEntry extends ViewRecord
{
    protected static string $resource = SafeEntryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
