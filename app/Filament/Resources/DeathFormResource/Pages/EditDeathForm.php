<?php

namespace App\Filament\Resources\DeathFormResource\Pages;

use App\Filament\Resources\DeathFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeathForm extends EditRecord
{
    protected static string $resource = DeathFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
