<?php

namespace App\Filament\Resources\ProjectClosureReasonResource\Pages;

use App\Filament\Resources\ProjectClosureReasonResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectClosureReason extends ViewRecord
{
    protected static string $resource = ProjectClosureReasonResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
