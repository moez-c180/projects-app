<?php

namespace App\Filament\Resources\FellowshipGrantFormResource\Pages;

use App\Filament\Resources\FellowshipGrantFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFellowshipGrantForms extends ListRecords
{
    protected static string $resource = FellowshipGrantFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
