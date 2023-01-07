<?php

namespace App\Filament\Resources\MembershipResource\Pages;

use App\Filament\Resources\MembershipResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberships extends ListRecords
{
    protected static string $resource = MembershipResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
