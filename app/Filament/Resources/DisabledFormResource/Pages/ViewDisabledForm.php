<?php

namespace App\Filament\Resources\DisabledFormResource\Pages;

use App\Filament\Resources\DisabledFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDisabledForm extends ViewRecord
{
    protected static string $resource = DisabledFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
