<?php

namespace App\Filament\Resources\MemberUnitResource\Pages;

use App\Filament\Resources\MemberUnitResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberUnit extends EditRecord
{
    protected static string $resource = MemberUnitResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
