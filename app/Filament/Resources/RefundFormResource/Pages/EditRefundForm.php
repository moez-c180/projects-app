<?php

namespace App\Filament\Resources\RefundFormResource\Pages;

use App\Filament\Resources\RefundFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRefundForm extends EditRecord
{
    protected static string $resource = RefundFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
