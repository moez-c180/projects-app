<?php

namespace App\Filament\Resources\FinancialBranchResource\Pages;

use App\Filament\Resources\FinancialBranchResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinancialBranches extends ListRecords
{
    protected static string $resource = FinancialBranchResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
