<?php

namespace App\Filament\Resources\RelativeDeathFormResource\Pages;

use App\Filament\Resources\RelativeDeathFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRelativeDeathForm extends ViewRecord
{
    protected static string $resource = RelativeDeathFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
