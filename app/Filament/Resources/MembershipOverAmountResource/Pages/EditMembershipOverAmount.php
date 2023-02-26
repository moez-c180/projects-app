<?php

namespace App\Filament\Resources\MembershipOverAmountResource\Pages;

use App\Filament\Resources\MembershipOverAmountResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMembershipOverAmount extends EditRecord
{
    protected static string $resource = MembershipOverAmountResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
