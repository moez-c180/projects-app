<?php

namespace App\Filament\Resources\RelativeDegreeDeathFormResource\Pages;

use App\Filament\Resources\RelativeDegreeDeathFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRelativeDegreeDeathForm extends EditRecord
{
    protected static string $resource = RelativeDegreeDeathFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
