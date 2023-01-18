<?php

namespace App\Filament\Resources\MarriageFormResource\Pages;

use App\Filament\Resources\MarriageFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMarriageForm extends ViewRecord
{
    protected static string $resource = MarriageFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
