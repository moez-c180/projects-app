<?php

namespace App\Filament\Resources\MemberPromotionResource\Pages;

use App\Filament\Resources\MemberPromotionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberPromotions extends ListRecords
{
    protected static string $resource = MemberPromotionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
