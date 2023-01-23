<?php

namespace App\Filament\Resources\DeathFormResource\Pages;

use App\Filament\Resources\DeathFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeathForms extends ListRecords
{
    protected static string $resource = DeathFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
