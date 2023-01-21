<?php

namespace App\Filament\Resources\RefundFormResource\Pages;

use App\Filament\Resources\RefundFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRefundForm extends ViewRecord
{
    protected static string $resource = RefundFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
