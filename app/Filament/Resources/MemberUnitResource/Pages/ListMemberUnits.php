<?php

namespace App\Filament\Resources\MemberUnitResource\Pages;

use App\Filament\Resources\MemberUnitResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberUnits extends ListRecords
{
    protected static string $resource = MemberUnitResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
