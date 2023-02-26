<?php

namespace App\Filament\Resources\MembershipSheetImportResource\Pages;

use App\Filament\Resources\MembershipSheetImportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMembershipSheetImport extends CreateRecord
{
    protected static string $resource = MembershipSheetImportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
