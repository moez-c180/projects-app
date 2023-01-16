<?php

namespace App\Filament\Resources\MemberPromotionResource\Pages;

use App\Filament\Resources\MemberPromotionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMemberPromotion extends ViewRecord
{
    protected static string $resource = MemberPromotionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
