<?php

namespace App\Filament\Resources\MembershipOverAmountResource\Pages;

use App\Filament\Resources\MembershipOverAmountResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMembershipOverAmount extends ViewRecord
{
    protected static string $resource = MembershipOverAmountResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
