<?php

namespace App\Filament\Resources\RankResource\Pages;

use App\Filament\Resources\RankResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRank extends EditRecord
{
    protected static string $resource = RankResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
