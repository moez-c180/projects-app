<?php

namespace App\Filament\Resources\AgeFormResource\Pages;

use App\Filament\Resources\AgeFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAgeForm extends ViewRecord
{
    protected static string $resource = AgeFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
