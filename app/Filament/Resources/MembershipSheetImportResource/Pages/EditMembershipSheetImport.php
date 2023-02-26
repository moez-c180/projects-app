<?php

namespace App\Filament\Resources\MembershipSheetImportResource\Pages;

use App\Filament\Resources\MembershipSheetImportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMembershipSheetImport extends EditRecord
{
    protected static string $resource = MembershipSheetImportResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
