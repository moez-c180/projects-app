<?php

namespace App\Filament\Resources\BankNameResource\Pages;

use App\Filament\Resources\BankNameResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBankName extends EditRecord
{
    protected static string $resource = BankNameResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
