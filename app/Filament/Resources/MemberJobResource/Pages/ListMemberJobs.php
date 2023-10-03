<?php

namespace App\Filament\Resources\MemberJobResource\Pages;

use App\Filament\Resources\MemberJobResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberJobs extends ListRecords
{
    protected static string $resource = MemberJobResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
