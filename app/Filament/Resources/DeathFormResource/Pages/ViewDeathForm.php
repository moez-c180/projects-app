<?php

namespace App\Filament\Resources\DeathFormResource\Pages;

use App\Filament\Resources\DeathFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDeathForm extends ViewRecord
{
    protected static string $resource = DeathFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
