<?php

namespace App\Filament\Resources\FinancialBranchResource\Pages;

use App\Filament\Resources\FinancialBranchResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinancialBranch extends EditRecord
{
    protected static string $resource = FinancialBranchResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
