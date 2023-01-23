<?php

namespace App\Filament\Resources\FellowshipGrantFormResource\Pages;

use App\Filament\Resources\FellowshipGrantFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFellowshipGrantForm extends EditRecord
{
    protected static string $resource = FellowshipGrantFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
