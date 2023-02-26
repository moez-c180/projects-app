<?php

namespace App\Filament\Resources\MemberWalletResource\Pages;

use App\Filament\Resources\MemberWalletResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberWallet extends EditRecord
{
    protected static string $resource = MemberWalletResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
