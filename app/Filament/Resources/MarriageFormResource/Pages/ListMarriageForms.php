<?php

namespace App\Filament\Resources\MarriageFormResource\Pages;

use App\Filament\Resources\MarriageFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarriageForms extends ListRecords
{
    protected static string $resource = MarriageFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
