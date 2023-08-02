<?php

namespace App\Filament\Resources\MemberUnitResource\Pages;

use App\Filament\Resources\MemberUnitResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMemberUnit extends ViewRecord
{
    protected static string $resource = MemberUnitResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
