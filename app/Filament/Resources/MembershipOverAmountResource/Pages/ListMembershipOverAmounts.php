<?php

namespace App\Filament\Resources\MembershipOverAmountResource\Pages;

use App\Filament\Resources\MembershipOverAmountResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMembershipOverAmounts extends ListRecords
{
    protected static string $resource = MembershipOverAmountResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
