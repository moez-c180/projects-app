<?php

namespace App\Filament\Resources\RelativeDeathFormResource\Pages;

use App\Filament\Resources\RelativeDeathFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRelativeDeathForms extends ListRecords
{
    protected static string $resource = RelativeDeathFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
