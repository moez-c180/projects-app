<?php

namespace App\Filament\Resources\BankNameResource\Pages;

use App\Filament\Resources\BankNameResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBankNames extends ListRecords
{
    protected static string $resource = BankNameResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
