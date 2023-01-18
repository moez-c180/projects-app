<?php

namespace App\Filament\Resources\ProjectClosureReasonResource\Pages;

use App\Filament\Resources\ProjectClosureReasonResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectClosureReason extends EditRecord
{
    protected static string $resource = ProjectClosureReasonResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
