<?php

namespace App\Filament\Resources\MemberPromotionResource\Pages;

use App\Filament\Resources\MemberPromotionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMemberPromotion extends CreateRecord
{
    protected static string $resource = MemberPromotionResource::class;

    protected function afterCreate(): void
    {
        $this->record->member->update(['rank_id' => $this->record->rank_id]);
    }
}
