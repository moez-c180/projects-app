<?php

namespace App\Filament\Resources\MemberJobResource\Pages;

use App\Filament\Resources\MemberJobResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMemberJob extends ViewRecord
{
    protected static string $resource = MemberJobResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
