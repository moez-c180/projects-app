<?php

namespace App\Filament\Resources\DisabledFormResource\Pages;

use App\Filament\Resources\DisabledFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisabledForm extends EditRecord
{
    protected static string $resource = DisabledFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
