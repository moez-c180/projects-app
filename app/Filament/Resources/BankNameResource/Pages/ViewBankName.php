<?php

namespace App\Filament\Resources\BankNameResource\Pages;

use App\Filament\Resources\BankNameResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBankName extends ViewRecord
{
    protected static string $resource = BankNameResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
