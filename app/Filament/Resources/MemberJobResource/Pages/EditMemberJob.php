<?php

namespace App\Filament\Resources\MemberJobResource\Pages;

use App\Filament\Resources\MemberJobResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberJob extends EditRecord
{
    protected static string $resource = MemberJobResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
