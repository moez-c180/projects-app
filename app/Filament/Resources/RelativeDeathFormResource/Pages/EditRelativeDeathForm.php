<?php

namespace App\Filament\Resources\RelativeDeathFormResource\Pages;

use App\Filament\Resources\RelativeDeathFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRelativeDeathForm extends EditRecord
{
    protected static string $resource = RelativeDeathFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
