<?php

namespace App\Filament\Resources\AgeFormResource\Pages;

use App\Filament\Resources\AgeFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgeForm extends EditRecord
{
    protected static string $resource = AgeFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
