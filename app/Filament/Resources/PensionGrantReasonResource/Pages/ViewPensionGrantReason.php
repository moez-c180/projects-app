<?php

namespace App\Filament\Resources\PensionGrantReasonResource\Pages;

use App\Filament\Resources\PensionGrantReasonResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPensionGrantReason extends ViewRecord
{
    protected static string $resource = PensionGrantReasonResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
