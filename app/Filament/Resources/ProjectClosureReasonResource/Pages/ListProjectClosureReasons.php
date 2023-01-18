<?php

namespace App\Filament\Resources\ProjectClosureReasonResource\Pages;

use App\Filament\Resources\ProjectClosureReasonResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectClosureReasons extends ListRecords
{
    protected static string $resource = ProjectClosureReasonResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
