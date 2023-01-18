<?php

namespace App\Filament\Resources\AgeFormResource\Pages;

use App\Filament\Resources\AgeFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgeForms extends ListRecords
{
    protected static string $resource = AgeFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
