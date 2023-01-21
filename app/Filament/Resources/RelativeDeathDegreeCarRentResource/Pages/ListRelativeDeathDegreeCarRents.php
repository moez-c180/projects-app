<?php

namespace App\Filament\Resources\RelativeDeathDegreeCarRentResource\Pages;

use App\Filament\Resources\RelativeDeathDegreeCarRentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRelativeDeathDegreeCarRents extends ListRecords
{
    protected static string $resource = RelativeDeathDegreeCarRentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
