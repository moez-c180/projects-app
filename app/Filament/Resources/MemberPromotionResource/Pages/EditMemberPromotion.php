<?php

namespace App\Filament\Resources\MemberPromotionResource\Pages;

use App\Filament\Resources\MemberPromotionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberPromotion extends EditRecord
{
    protected static string $resource = MemberPromotionResource::class;

    protected function afterSave(): void
    {
        dd("asd");
    }

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
