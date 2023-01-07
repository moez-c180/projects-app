<?php

namespace App\Filament\Resources\MembershipResource\Pages;

use App\Filament\Resources\MembershipResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMembership extends EditRecord
{
    protected static string $resource = MembershipResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
