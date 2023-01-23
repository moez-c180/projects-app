<?php

namespace App\Filament\Resources\DisabledFormResource\Pages;

use App\Filament\Resources\DisabledFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisabledForms extends ListRecords
{
    protected static string $resource = DisabledFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
