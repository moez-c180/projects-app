<?php

namespace App\Filament\Resources\ProjectClosureFormResource\Pages;

use App\Filament\Resources\ProjectClosureFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectClosureForms extends ListRecords
{
    protected static string $resource = ProjectClosureFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
