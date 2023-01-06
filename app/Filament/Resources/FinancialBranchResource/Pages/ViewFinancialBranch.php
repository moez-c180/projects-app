<?php

namespace App\Filament\Resources\FinancialBranchResource\Pages;

use App\Filament\Resources\FinancialBranchResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFinancialBranch extends ViewRecord
{
    protected static string $resource = FinancialBranchResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
