<?php

namespace App\Filament\Resources\MarriageFormResource\Pages;

use App\Filament\Resources\MarriageFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarriageForm extends EditRecord
{
    protected static string $resource = MarriageFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
