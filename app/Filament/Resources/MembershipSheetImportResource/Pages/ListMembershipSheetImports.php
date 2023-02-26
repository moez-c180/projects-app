<?php

namespace App\Filament\Resources\MembershipSheetImportResource\Pages;

use App\Filament\Resources\MembershipSheetImportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMembershipSheetImports extends ListRecords
{
    protected static string $resource = MembershipSheetImportResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
