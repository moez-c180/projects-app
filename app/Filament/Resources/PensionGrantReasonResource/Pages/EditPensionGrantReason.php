<?php

namespace App\Filament\Resources\PensionGrantReasonResource\Pages;

use App\Filament\Resources\PensionGrantReasonResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPensionGrantReason extends EditRecord
{
    protected static string $resource = PensionGrantReasonResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
