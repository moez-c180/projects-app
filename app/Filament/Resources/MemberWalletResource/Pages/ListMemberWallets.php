<?php

namespace App\Filament\Resources\MemberWalletResource\Pages;

use App\Filament\Resources\MemberWalletResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberWallets extends ListRecords
{
    protected static string $resource = MemberWalletResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
